<?php

namespace App\Livewire;

use Livewire\Component;

class HeaderCartBadge extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $cartCount = 0;
        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $cartCount += $item['quantity'];
        }

        return view('livewire.header-cart-badge', compact('cartCount'));
    }
}
