<div>
    @if($showQuantitySelector)
        <div class="flex" style="gap: 15px; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0; width: 100px;">
                <label class="form-label" for="quantity" style="font-size: 0.8rem;">Qty</label>
                <input type="number" wire:model="quantity" id="quantity" min="1" class="form-control" style="text-align: center;">
            </div>
            <button type="button" wire:click="addToCart" class="{{ $styleClass }}" style="flex-grow: 1; height: 50px;">
                @if($showIcon)
                    <i class="fa-solid fa-cart-shopping"></i>
                @endif
                {{ $text }}
            </button>
        </div>
    @else
        <button type="button" wire:click="addToCart" class="{{ $styleClass }}">
            @if($showIcon)
                <i class="fa-solid fa-cart-plus"></i>
            @endif
            {{ $text }}
        </button>
    @endif
</div>
