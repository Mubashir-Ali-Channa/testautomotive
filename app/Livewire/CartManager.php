<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class CartManager extends Component
{
    public $cart = [];
    public $total = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = session()->get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    public function updateQuantity($productId, $qty)
    {
        $qty = intval($qty);
        if ($qty < 1) {
            $this->removeItem($productId);
            return;
        }

        if ($qty > 3) {
            $this->dispatch('toast', message: 'Maximum quantity of 3 reached per product.', status: 'warning');
            $qty = 3;
        }

        $product = Product::find($productId);
        if ($product) {
            if ($qty > $product->stock) {
                $this->dispatch('toast', message: 'Quantity for ' . $product->name . ' exceeds available stock.', status: 'error');
                $qty = $product->stock; // Cap at max stock
            }
            
            $this->cart[$productId]['quantity'] = $qty;
            session()->put('cart', $this->cart);
            $this->calculateTotal();
            
            $this->dispatch('cartUpdated');
        }
    }

    public function incrementQuantity($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->updateQuantity($productId, $this->cart[$productId]['quantity'] + 1);
        }
    }

    public function decrementQuantity($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->updateQuantity($productId, $this->cart[$productId]['quantity'] - 1);
        }
    }

    public function removeItem($productId)
    {
        if (isset($this->cart[$productId])) {
            $name = $this->cart[$productId]['name'];
            unset($this->cart[$productId]);
            session()->put('cart', $this->cart);
            $this->calculateTotal();
            
            $this->dispatch('cartUpdated');
            $this->dispatch('toast', message: $name . ' removed from cart.', status: 'success');
        }
    }

    public function render()
    {
        return view('livewire.cart-manager');
    }
}
