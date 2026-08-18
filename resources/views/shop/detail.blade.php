@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <!-- Breadcrumbs / Back button -->
    <section style="background-color: var(--bg-white); padding: 15px 0; border-bottom: 1px solid var(--border-light);">
        <div class="container flex" style="font-size: 0.9rem; color: var(--text-muted);">
            <a href="{{ route('shop') }}"><i class="fa-solid fa-arrow-left-long" style="margin-right: 5px;"></i> Back to Shop</a>
            <span>/</span>
            <span class="text-primary">{{ $product->name }}</span>
        </div>
    </section>

    <!-- Product Detail Block -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="product-detail-grid">
                
                <!-- Image Gallery (Alpine.js) -->
                @php
                    $images = $product->image_paths ?? [];
                    if (empty($images)) {
                        $images = ['https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800'];
                    }
                @endphp
                <div x-data="{
                        images: {{ json_encode($images) }},
                        activeIndex: 0,
                        lightboxOpen: false,
                        zoomLevel: 1,
                        prev() { this.activeIndex = this.activeIndex === 0 ? this.images.length - 1 : this.activeIndex - 1 },
                        next() { this.activeIndex = this.activeIndex === this.images.length - 1 ? 0 : this.activeIndex + 1 },
                        zoomIn() { if (this.zoomLevel < 2) this.zoomLevel += 0.2 },
                        zoomOut() { if (this.zoomLevel > 0.6) this.zoomLevel -= 0.2 }
                     }">
                    
                    <!-- Main Image Display -->
                    <div style="border: 1px solid var(--border-light); border-radius: 8px; overflow: hidden; background-color: var(--bg-light); height: 420px; box-shadow: var(--shadow); position: relative;">
                        <img :src="images[activeIndex]" alt="{{ $product->name }}" @click="lightboxOpen = true; zoomLevel = 1" style="width: 100%; height: 100%; object-fit: cover; cursor: zoom-in; transition: var(--transition);">
                        
                        @if(count($images) > 1)
                            <!-- Left/Right navigation overlays on main image -->
                            <button type="button" @click.stop="prev()" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color:#fff; border:none; width:40px; height:40px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1rem; transition: var(--transition); z-index: 10;">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button type="button" @click.stop="next()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color:#fff; border:none; width:40px; height:40px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1rem; transition: var(--transition); z-index: 10;">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Thumbnails list -->
                    @if(count($images) > 1)
                        <div class="flex" style="gap: 10px; margin-top: 15px; overflow-x: auto; padding-bottom: 5px;">
                            <template x-for="(img, idx) in images" :key="idx">
                                <div @click="activeIndex = idx" 
                                     :style="activeIndex === idx ? 'border-color: var(--primary); transform: scale(1.03);' : ''"
                                     style="width: 80px; height: 80px; border: 2px solid var(--border-light); border-radius: 6px; overflow: hidden; cursor: pointer; background-color: var(--bg-white); box-shadow: var(--shadow); flex-shrink: 0; transition: var(--transition);">
                                    <img :src="img" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </template>
                        </div>
                    @endif

                    <!-- Lightbox Modal -->
                    <div x-show="lightboxOpen" class="lightbox-modal" style="display: none;" @keydown.escape.window="lightboxOpen = false">
                        <div class="lightbox-content">
                            <button type="button" @click="lightboxOpen = false" class="lightbox-close" aria-label="Close Lightbox">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <button type="button" @click="zoomIn()" class="lightbox-zoom-btn" style="right: 90px;" aria-label="Zoom In">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </button>
                            <button type="button" @click="zoomOut()" class="lightbox-zoom-btn" style="right: 50px;" aria-label="Zoom Out">
                                <i class="fa-solid fa-magnifying-glass-minus"></i>
                            </button>
                            
                            <img :src="images[activeIndex]" class="lightbox-img" :style="'transform: scale(' + zoomLevel + ')'" alt="">
                        </div>
                    </div>
                </div>

                <!-- Specs & Actions -->
                <div>
                    <span class="badge badge-processing" style="margin-bottom: 10px;">{{ $product->category }}</span>
                    <h1 style="font-size: 2.8rem; text-transform: uppercase; margin-bottom: 10px; line-height: 1.2;">{{ $product->name }}</h1>
                    
                    <div class="flex" style="gap: 10px; margin-bottom: 20px; align-items: center;">
                        <div class="testimonial-rating" style="font-size: 1rem; margin-bottom: 0;">
                            @php
                                $avg = $product->average_rating;
                                $fullStars = floor($avg);
                                $halfStar = ($avg - $fullStars) >= 0.5 ? 1 : 0;
                                $emptyStars = 5 - $fullStars - $halfStar;
                            @endphp
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            @if($halfStar)
                                <i class="fa-solid fa-star-half-stroke"></i>
                            @endif
                            @for($i = 0; $i < $emptyStars; $i++)
                                <i class="fa-regular fa-star"></i>
                            @endfor
                        </div>
                        <strong style="font-size: 0.95rem; color: var(--text-dark);">{{ $avg }} / 5.0</strong>
                        <span class="text-muted" style="font-size: 0.9rem;">({{ $product->reviews()->where('is_approved', true)->count() }} approved reviews)</span>
                    </div>
                    
                    <div style="margin-bottom: 25px; display: flex; align-items: center; gap: 20px;">
                        <span style="font-size: 2.2rem; font-weight: 800; color: var(--primary);">${{ number_format($product->price, 2) }}</span>
                        @if($product->stock > 0)
                            <span class="badge badge-completed"><i class="fa-solid fa-check"></i> In Stock ({{ $product->stock }} left)</span>
                        @else
                            <span class="badge badge-cancelled"><i class="fa-solid fa-xmark"></i> Out of Stock</span>
                        @endif
                    </div>

                    <p class="text-muted" style="font-size: 1.05rem; line-height: 1.7; margin-bottom: 30px;">
                        {{ $product->description }}
                    </p>

                    <!-- Add to Cart Form (Livewire AddToCartButton Component with Qty Selector) -->
                    @if($product->stock > 0)
                        <div class="card" style="padding: 25px; background-color: var(--bg-white); margin-bottom: 40px; border-color: var(--border-light);">
                            <livewire:add-to-cart-button 
                                :productId="$product->id" 
                                :showQuantitySelector="true" 
                                text="Add To Shopping Cart" 
                                styleClass="btn btn-primary" 
                            />
                        </div>
                    @else
                        <div class="card" style="padding: 25px; text-align: center; border-color: var(--danger); background-color: var(--bg-white); margin-bottom: 40px;">
                            <h4 style="color: var(--danger); text-transform: uppercase; margin-bottom: 5px;">Currently Sold Out</h4>
                            <p class="text-muted" style="font-size: 0.9rem;">Contact us to reserve the next arrival or request a custom build quotation.</p>
                        </div>
                    @endif

                    <!-- Specifications Table -->
                    @if(!empty($product->specifications))
                        <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 15px; border-bottom: 2px solid var(--primary); padding-bottom: 8px;">Specifications</h3>
                        <table class="table" style="font-size: 0.95rem; margin-bottom: 0;">
                            @foreach($product->specifications as $key => $val)
                                <tr>
                                    <th style="width: 40%; font-weight: 700; color: var(--text-muted); border-bottom: 1px solid var(--border-light); padding: 10px 0;">{{ $key }}</th>
                                    <td style="border-bottom: 1px solid var(--border-light); padding: 10px 0; color: var(--text-dark);">{{ $val }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif

                </div>

            </div>

            <!-- Reviews Section -->
            @livewire('product-reviews', ['product' => $product])

            <!-- Related Products Section -->
            @if($relatedProducts->isNotEmpty())
                <div style="margin-top: 80px; border-top: 1px solid var(--border-light); padding-top: 50px;">
                    <h2 style="font-size: 2.2rem; text-transform: uppercase; margin-bottom: 35px; text-align: center;">Related Products</h2>
                    <div class="grid grid-4">
                        @foreach($relatedProducts as $related)
                            <div class="card">
                                <div class="card-img-wrapper" style="height: 180px;">
                                    @php
                                        $relImages = $related->image_paths;
                                        $relFirstImage = is_array($relImages) && count($relImages) > 0 ? $relImages[0] : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=300';
                                    @endphp
                                    <a href="{{ route('product.detail', $related->slug) }}">
                                        <img src="{{ $relFirstImage }}" alt="{{ $related->name }}">
                                    </a>
                                    <div style="position: absolute; top: 10px; right: 10px; background-color: var(--primary); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 2px 6px; border-radius: 2px; text-transform: uppercase;">
                                        {{ $related->category }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title" style="font-size: 1.15rem; margin-bottom: 10px;"><a href="{{ route('product.detail', $related->slug) }}">{{ $related->name }}</a></h3>
                                    <div class="flex-between">
                                        <span style="font-size: 1.2rem; font-weight: 800; color: var(--primary);">${{ number_format($related->price, 2) }}</span>
                                        <a href="{{ route('product.detail', $related->slug) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
