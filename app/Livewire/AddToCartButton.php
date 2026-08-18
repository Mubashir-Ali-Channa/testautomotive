<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class AddToCartButton extends Component
{
    public $productId;
    public $quantity = 1;
    public $styleClass = 'btn btn-primary';
    public $text = 'Add';
    public $showIcon = true;
    public $showQuantitySelector = false;

    public function mount($productId, $quantity = 1, $styleClass = 'btn btn-primary', $text = 'Add', $showIcon = true, $showQuantitySelector = false)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->styleClass = $styleClass;
        $this->text = $text;
        $this->showIcon = $showIcon;
        $this->showQuantitySelector = $showQuantitySelector;
    }

    public function addToCart()
    {
        $product = Product::find($this->productId);
        
        if (!$product) {
            $this->dispatch('toast', message: 'Product not found.', status: 'error');
            return;
        }

        if ($this->quantity > 3) {
            $this->dispatch('toast', message: 'You cannot order more than 3 of this product.', status: 'warning');
            return;
        }

        if ($this->quantity > $product->stock) {
            $this->dispatch('toast', message: 'Requested quantity exceeds available stock.', status: 'error');
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$this->productId])) {
            $newQty = $cart[$this->productId]['quantity'] + $this->quantity;
            if ($newQty > 3) {
                $this->dispatch('toast', message: 'Maximum quantity of 3 reached for this product.', status: 'warning');
                return;
            }
            if ($newQty > $product->stock) {
                $this->dispatch('toast', message: 'Cannot add more. Exceeds available stock.', status: 'error');
                return;
            }
            $cart[$this->productId]['quantity'] = $newQty;
        } else {
            $images = $product->image_paths;
            $image = is_array($images) && count($images) > 0 ? $images[0] : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=300';
            
            $cart[$this->productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $this->quantity,
                'image' => $image,
                'slug' => $product->slug
            ];
        }

        session()->put('cart', $cart);

        // Notify other components
        $this->dispatch('cartUpdated');

        // Dispatch Alpine Toast
        $this->dispatch('toast', message: $product->name . ' added to cart!', status: 'success');
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
