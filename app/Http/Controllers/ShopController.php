<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Product::select('category')->distinct()->pluck('category');

        return view('shop.index', compact('products', 'categories'));
    }

    public function product($slug)
    {
        $product = Product::active()->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::active()->where('category', $product->category)
                                  ->where('id', '!=', $product->id)
                                  ->take(4)
                                  ->get();

        return view('shop.detail', compact('product', 'relatedProducts'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('shop.cart', compact('cart'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $qty = $request->input('quantity', 1);

        if ($qty > $product->stock) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $qty;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Cannot add more. Exceeds available stock.');
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            $images = $product->image_paths;
            $image = is_array($images) && count($images) > 0 ? $images[0] : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=300';
            
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'image' => $image,
                'slug' => $product->slug
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', $product->name . ' added to cart!');
    }

    public function updateCart(Request $request)
    {
        $quantities = $request->input('quantities', []);
        $cart = session()->get('cart', []);

        foreach ($quantities as $id => $qty) {
            if (isset($cart[$id])) {
                $product = Product::find($id);
                if ($product) {
                    $qty = max(1, intval($qty));
                    if ($qty > $product->stock) {
                        return back()->with('error', 'Quantity for ' . $product->name . ' exceeds available stock.');
                    }
                    $cart[$id]['quantity'] = $qty;
                }
            }
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Cart updated successfully.');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach ($cart as $id => $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('shop.checkout', compact('cart', 'total'));
    }

    public function submitCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Calculate total
        $total = 0;
        foreach ($cart as $id => $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'status' => 'pending',
            'total' => $total,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        // Save order items & decrement stock
        foreach ($cart as $id => $item) {
            $product = Product::findOrFail($id);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Decrement stock
            $product->stock = max(0, $product->stock - $item['quantity']);
            $product->save();
        }

        // Clear cart session
        session()->forget('cart');

        return redirect()->route('shop')->with('success_order', 'Thank you! Your order #' . $order->id . ' has been placed successfully.');
    }

    public function myAccount()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        return view('shop.my_account', compact('orders'));
    }
}
