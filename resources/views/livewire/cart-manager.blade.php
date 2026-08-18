<div>
    @if(empty($cart))
        <div class="card" style="padding: 60px; text-align: center; max-width: 600px; margin: 0 auto;">
            <div style="font-size: 4rem; color: var(--text-muted); margin-bottom: 20px;"><i class="fa-solid fa-cart-flatbed-empty"></i></div>
            <h2 style="text-transform: uppercase; margin-bottom: 10px;">Your Cart is Empty</h2>
            <p class="text-muted" style="margin-bottom: 30px;">You have no items in your cart. Head over to our catalog to add parts, gear, or bikes!</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">
                Browse Shop Catalog <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;" x-data="{
            items: {
                @foreach($cart as $id => $item)
                    '{{ $id }}': { quantity: {{ $item['quantity'] }}, price: {{ $item['price'] }} },
                @endforeach
            },
            get total() {
                return Object.values(this.items).reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },
            formatPrice(val) {
                return '$' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }">
            
            <!-- Cart Items List -->
            <div class="card" style="padding: 25px;">
                <div class="table-responsive">
                    <table class="table" style="vertical-align: middle;">
                        <thead>
                            <tr>
                                <th colspan="2">Product</th>
                                <th>Price</th>
                                <th style="width: 150px; text-align: center;">Quantity</th>
                                <th style="text-align: right;">Subtotal</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                                <tr>
                                    <td style="width: 80px;">
                                        <a href="{{ route('product.detail', $item['slug']) }}">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-light);">
                                        </a>
                                    </td>
                                    <td>
                                        <h4 style="font-size: 1.1rem; text-transform: uppercase; margin-bottom: 3px;">
                                            <a href="{{ route('product.detail', $item['slug']) }}">{{ $item['name'] }}</a>
                                        </h4>
                                    </td>
                                    <td style="font-size: 1.1rem; font-weight: 600;">
                                        ${{ number_format($item['price'], 2) }}
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="flex" style="justify-content: center; gap: 5px;">
                                            <button type="button" @click="if (items['{{ $id }}'].quantity > 1) { items['{{ $id }}'].quantity--; $wire.decrementQuantity('{{ $id }}'); }" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.8rem;">-</button>
                                            <input type="number" :value="items['{{ $id }}'].quantity" readonly class="form-control" style="text-align: center; padding: 4px; width: 60px; display: inline-block; background-color: transparent; border: none; font-weight: 700; pointer-events: none;">
                                            <button type="button" @click="if (items['{{ $id }}'].quantity < 3) { items['{{ $id }}'].quantity++; $wire.incrementQuantity('{{ $id }}'); } else { $dispatch('toast', { message: 'Maximum quantity of 3 reached per product.', status: 'warning' }); }" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.8rem;">+</button>
                                        </div>
                                    </td>
                                    <td style="font-size: 1.1rem; font-weight: 700; color: var(--primary); text-align: right;" x-text="formatPrice(items['{{ $id }}'].price * items['{{ $id }}'].quantity)">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" wire:click="removeItem('{{ $id }}')" class="btn btn-danger" style="padding: 6px 10px; font-size: 0.85rem;" title="Remove Item">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px;">
                    <a href="{{ route('shop') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Summary Panel -->
            <div class="card" style="padding: 30px; position: sticky; top: 100px;">
                <h3 style="font-size: 1.4rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid var(--primary); padding-bottom: 8px;">Order Summary</h3>
                
                <div class="flex-between" style="font-size: 1.1rem; margin-bottom: 15px;">
                    <span style="color: var(--text-muted);">Cart Subtotal:</span>
                    <span style="font-weight: 600;" x-text="formatPrice(total)">${{ number_format($total, 2) }}</span>
                </div>

                <div class="flex-between" style="font-size: 1.1rem; margin-bottom: 25px; border-bottom: 1px solid var(--border-light); padding-bottom: 15px;">
                    <span style="color: var(--text-muted);">Shipping:</span>
                    <span class="text-primary" style="font-weight: 700; text-transform: uppercase;">Free</span>
                </div>

                <div class="flex-between" style="font-size: 1.4rem; font-weight: 800; margin-bottom: 30px;">
                    <span>Total Price:</span>
                    <span style="color: var(--primary);" x-text="formatPrice(total)">${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn btn-primary" style="width: 100%; height: 50px;">
                    Proceed To Checkout <i class="fa-solid fa-credit-card" style="margin-left: 5px;"></i>
                </a>
            </div>

        </div>
    @endif
</div>
