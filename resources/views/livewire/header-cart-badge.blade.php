<div>
    <a href="{{ route('cart') }}" class="cart-icon-btn">
        <i class="fa-solid fa-cart-shopping"></i>
        @if($cartCount > 0)
            <span class="cart-badge">{{ $cartCount }}</span>
        @endif
    </a>
</div>
