<div x-data="{ loading: false }" @click="if ($event.target.closest('button') || $event.target.closest('select') || $event.target.closest('input')) loading = true" @filter-updated.window="loading = false">
    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 40px; align-items: start;">
        
        <!-- Filters Sidebar -->
        <div class="card" style="padding: 25px; position: sticky; top: 100px; background-color: var(--bg-card);">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid var(--primary); padding-bottom: 8px;">Filters</h3>
            
            <!-- Search Box -->
            <div class="form-group">
                <label class="form-label">Search</label>
                <div style="position: relative;">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search item..." style="padding-right: 40px;">
                    <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                </div>
            </div>

            <!-- Categories -->
            <div class="form-group" style="margin-top: 25px;">
                <label class="form-label">Category</label>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <button type="button" wire:click="selectCategory('')" class="flex" style="background: none; border: none; text-align: left; cursor: pointer; gap: 10px; color: {{ empty($category) ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: {{ empty($category) ? '700' : 'normal' }}; font-size: 1rem; font-family: var(--font-body);">
                        <i class="fa-solid {{ empty($category) ? 'fa-dot-circle' : 'fa-circle' }}" style="font-size: 0.8rem;"></i> All Categories
                    </button>
                    @foreach($categories as $cat)
                        <button type="button" wire:click="selectCategory('{{ $cat }}')" class="flex" style="background: none; border: none; text-align: left; cursor: pointer; gap: 10px; color: {{ $category === $cat ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: {{ $category === $cat ? '700' : 'normal' }}; font-size: 1rem; font-family: var(--font-body);">
                            <i class="fa-solid {{ $category === $cat ? 'fa-dot-circle' : 'fa-circle' }}" style="font-size: 0.8rem;"></i> {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Sorting -->
            <div class="form-group" style="margin-top: 25px;">
                <label class="form-label">Sort By</label>
                <select wire:model.live="sort" class="form-control">
                    <option value="latest">Latest Arrivals</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                </select>
            </div>
        </div>

        <!-- Products Grid -->
        <div style="position: relative; width: 100%;">
            <!-- Dynamic Spinner Overlay -->
            <div wire:loading style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.75); z-index: 10; border-radius: 8px;">
                <div style="display: flex; align-items: flex-start; justify-content: center; width: 100%; height: 100%; padding-top: 100px;">
                    <div style="text-align: center; position: sticky; top: 200px;">
                        <i class="fa-solid fa-circle-notch fa-spin text-primary" style="font-size: 3rem; color: var(--primary);"></i>
                        <p style="margin-top: 15px; font-weight: 800; color: var(--text-dark); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.5px;">Fetching Shop Inventory...</p>
                    </div>
                </div>
            </div>

            <div :class="{ 'opacity-50': loading }" wire:loading.class="opacity-50" style="transition: opacity 0.2s ease;">
                @if($products->isEmpty())
                    <div class="card" style="padding: 60px; text-align: center;">
                        <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 15px;"><i class="fa-solid fa-box-open"></i></div>
                        <h3 style="text-transform: uppercase;">No Products Found</h3>
                        <p class="text-muted" style="margin-top: 10px;">No inventory items match your search filters.</p>
                    </div>
                @else
                    <div class="grid grid-3">
                        @foreach($products as $product)
                            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <div class="card-img-wrapper" style="height: 200px;">
                                        @php
                                            $images = $product->image_paths;
                                            $firstImage = is_array($images) && count($images) > 0 ? $images[0] : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=300';
                                        @endphp
                                        <a href="{{ route('product.detail', $product->slug) }}">
                                            <img src="{{ $firstImage }}" alt="{{ $product->name }}">
                                        </a>
                                        
                                        <!-- Category Badge -->
                                        <div style="position: absolute; top: 10px; right: 10px; background-color: var(--primary); color: #000; font-size: 0.75rem; font-weight: 800; padding: 3px 8px; border-radius: 2px; text-transform: uppercase;">
                                            {{ $product->category }}
                                        </div>

                                        <!-- Out of stock badge -->
                                        @if($product->stock <= 0)
                                            <div style="position: absolute; bottom: 0; left: 0; width: 100%; text-align: center; background-color: rgba(239, 68, 68, 0.9); color: #fff; font-size: 0.8rem; font-weight: 700; padding: 5px 0; text-transform: uppercase;">
                                                Out of Stock
                                            </div>
                                        @endif
                                    </div>

                                    <div class="card-body">
                                        <h3 class="card-title" style="font-size: 1.3rem;">
                                            <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                        </h3>
                                        <p class="card-desc" style="font-size: 0.85rem; margin-bottom: 0;">{{ Str::limit($product->description, 90) }}</p>
                                    </div>
                                </div>

                                <div class="card-body" style="padding-top: 0; padding-bottom: 25px;">
                                    <div class="flex-between">
                                        <span style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">${{ number_format($product->price, 2) }}</span>
                                        
                                        @if($product->stock > 0)
                                            <livewire:add-to-cart-button :productId="$product->id" :key="'shop-add-'.$product->id" />
                                        @else
                                            <button class="btn btn-secondary" disabled style="padding: 8px 12px; font-size: 0.9rem; cursor: not-allowed; opacity: 0.6;">
                                                Sold Out
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div style="margin-top: 50px;">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
