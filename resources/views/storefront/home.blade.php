@extends('layouts.app')

@section('title', 'Premium Motorcycle Tuning & Custom Fabrication')
@section('meta_description', 'Welcome to TestAutomotive, the premier choice for custom motorcycle builds, mechanical engine tuning, parts, and workshop services.')

@section('content')

    <!-- Hero Auto-Slider Section -->
    <section class="hero-slider-wrapper" 
             x-data="{ 
                activeSlide: 1, 
                totalSlides: {{ $heroSlides->count() }}, 
                next() { this.activeSlide = this.activeSlide === this.totalSlides ? 1 : this.activeSlide + 1 }, 
                prev() { this.activeSlide = this.activeSlide === 1 ? this.totalSlides : this.activeSlide - 1 } 
             }"
             x-init="setInterval(() => next(), 6000)">
        
        @foreach($heroSlides as $index => $slide)
            <div class="hero-slide" :class="activeSlide === {{ $index + 1 }} ? 'active' : ''" style="background-image: url('{{ $slide->image_path }}');">
                <div class="hero-slide-overlay"></div>
                <div class="container" style="position: relative; z-index: 5; height: 100%; display: flex; align-items: center;">
                    <div class="hero-slide-content">
                        @if($slide->subtitle)
                            <div class="hero-subtitle">{{ $slide->subtitle }}</div>
                        @endif
                        <h1 class="hero-title">{{ $slide->title }}</h1>
                        <p class="hero-desc">
                            Professional motorcycle repairs, performance tuning, vintage restorations, and high-quality accessories built for riders who demand excellence.
                        </p>
                        <a href="{{ $slide->button_link }}" class="btn btn-primary">
                            {{ $slide->button_text }} <i class="fa-solid fa-arrow-right-long" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Navigation Arrows (Left/Right Middle) -->
        <button @click="prev()" class="hero-slider-arrow" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); z-index: 10; background-color: rgba(0,0,0,0.4); color: #fff; border: none; height: 50px; width: 50px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);" onmouseover="this.style.backgroundColor='var(--primary)'" onmouseout="this.style.backgroundColor='rgba(0,0,0,0.4)'" aria-label="Previous Slide">
            <i class="fa-solid fa-chevron-left" style="font-size: 1.2rem;"></i>
        </button>
        <button @click="next()" class="hero-slider-arrow" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); z-index: 10; background-color: rgba(0,0,0,0.4); color: #fff; border: none; height: 50px; width: 50px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);" onmouseover="this.style.backgroundColor='var(--primary)'" onmouseout="this.style.backgroundColor='rgba(0,0,0,0.4)'" aria-label="Next Slide">
            <i class="fa-solid fa-chevron-right" style="font-size: 1.2rem;"></i>
        </button>

        <!-- Slide Indicator Map Dots (Lower Middle) -->
        <div class="hero-slider-dots" style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 10;">
            @foreach($heroSlides as $index => $slide)
                <button @click="activeSlide = {{ $index + 1 }}" 
                        class="hero-slider-dot" 
                        style="height: 10px; width: 10px; border-radius: 50%; border: none; cursor: pointer; padding: 0; transition: var(--transition); background-color: rgba(255,255,255,0.4);"
                        :style="activeSlide === {{ $index + 1 }} ? 'background-color: var(--primary); transform: scale(1.25);' : ''"
                        aria-label="Go to slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
    </section>

    <!-- Top Products Section (Horizontal Slider if > 4 products) -->
    <section class="section section-light scroll-fade">
        <div class="container" x-data="{ 
            scrollNext() { this.$refs.slider.scrollBy({ left: 320, behavior: 'smooth' }) },
            scrollPrev() { this.$refs.slider.scrollBy({ left: -320, behavior: 'smooth' }) }
        }">
            
            <div class="flex-between" style="margin-bottom: 40px; align-items: flex-end;">
                <div>
                    <span class="hero-subtitle" style="font-size: 1.1rem; display: block; margin-bottom: 5px;">Top Products</span>
                    <h2 style="font-size: 2.8rem; text-transform: uppercase;">Featured Shop Inventory</h2>
                </div>
                
                @if($featuredProducts->count() > 4)
                    <div class="flex" style="gap: 8px;">
                        <button type="button" @click="scrollPrev()" class="btn btn-secondary" style="padding: 10px 15px; font-size: 0.95rem;">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" @click="scrollNext()" class="btn btn-secondary" style="padding: 10px 15px; font-size: 0.95rem;">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                @endif
            </div>

            @if($featuredProducts->count() > 4)
                <!-- Horizontal Scroll Track -->
                <div class="slider-container">
                    <div x-ref="slider" class="slider-track">
                        @foreach($featuredProducts as $product)
                            <div class="slider-item">
                                <div class="card" style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div>
                                        <div class="card-img-wrapper" style="height: 200px;">
                                            @php
                                                $images = $product->image_paths;
                                                $firstImage = is_array($images) && count($images) > 0 ? $images[0] : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=300';
                                            @endphp
                                            <a href="{{ route('product.detail', $product->slug) }}">
                                                <img src="{{ $firstImage }}" alt="{{ $product->name }}">
                                            </a>
                                            <div style="position: absolute; top: 10px; right: 10px; background-color: var(--primary); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 4px 10px; border-radius: 4px; text-transform: uppercase;">
                                                {{ $product->category }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title" style="font-size: 1.25rem;">
                                                <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                            </h3>
                                            <p class="card-desc" style="font-size: 0.85rem; margin-bottom: 0;">{{ Str::limit($product->description, 80) }}</p>
                                        </div>
                                    </div>
                                    <div class="card-body" style="padding-top: 0; padding-bottom: 25px;">
                                        <div class="flex-between">
                                            <span style="font-size: 1.3rem; font-weight: 800; color: var(--primary);">${{ number_format($product->price, 2) }}</span>
                                            <livewire:add-to-cart-button :productId="$product->id" :key="'featured-slider-'.$product->id" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Normal Grid -->
                <div class="grid grid-4">
                    @foreach($featuredProducts as $product)
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
                                    <div style="position: absolute; top: 10px; right: 10px; background-color: var(--primary); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 4px 10px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $product->category }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title" style="font-size: 1.25rem;">
                                        <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    <p class="card-desc" style="font-size: 0.85rem; margin-bottom: 0;">{{ Str::limit($product->description, 80) }}</p>
                                </div>
                            </div>
                            <div class="card-body" style="padding-top: 0; padding-bottom: 25px;">
                                <div class="flex-between">
                                    <span style="font-size: 1.3rem; font-weight: 800; color: var(--primary);">${{ number_format($product->price, 2) }}</span>
                                    <livewire:add-to-cart-button :productId="$product->id" :key="'featured-grid-'.$product->id" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="text-center" style="margin-top: 40px;">
                <a href="{{ route('shop') }}" class="btn btn-secondary">
                    View All Products <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- We Build, Your Ride Section -->
    <section class="section section-grey scroll-fade" style="border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
        <div class="container">
            <div class="grid grid-2" style="align-items: center; gap: 60px;">
                <div>
                    <img src="https://images.unsplash.com/photo-1599819811279-d5ad9cccf838?w=600" alt="Motorcycle custom fabrication" style="border-radius: 8px; border: 1px solid var(--border-light); box-shadow: var(--shadow); width:100%;">
                </div>
                <div>
                    <span class="hero-subtitle">Who We Are</span>
                    <h2 style="font-size: 2.8rem; text-transform: uppercase; margin-bottom: 20px;">We Build, You Ride</h2>
                    <p class="text-muted" style="font-size: 1.05rem; margin-bottom: 25px; line-height: 1.7;">
                        TestAutomotive has been the premier choice for custom motorcycle designs and advanced mechanical tuning since 2008. We don’t just swap parts; we sculpt bespoke machines tailored to your specifications.
                    </p>
                    
                    <ul style="list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 35px;">
                        <li class="flex" style="gap: 10px; font-weight: 600;"><i class="fa-solid fa-circle-check text-primary" style="font-size: 1.1rem;"></i> Custom Frame Fabrication</li>
                        <li class="flex" style="gap: 10px; font-weight: 600;"><i class="fa-solid fa-circle-check text-primary" style="font-size: 1.1rem;"></i> High Precision TIG Welding</li>
                        <li class="flex" style="gap: 10px; font-weight: 600;"><i class="fa-solid fa-circle-check text-primary" style="font-size: 1.1rem;"></i> Engine Dyno Calibration</li>
                        <li class="flex" style="gap: 10px; font-weight: 600;"><i class="fa-solid fa-circle-check text-primary" style="font-size: 1.1rem;"></i> Carburetor Restoration</li>
                    </ul>

                    <a href="{{ route('about') }}" class="btn btn-primary">
                        Learn More About Us <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem; margin-left: 5px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid List -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="text-center" style="margin-bottom: 50px;">
                <span class="hero-subtitle" style="font-size: 1.1rem;">Specialist Workshop</span>
                <h2 style="font-size: 2.8rem; text-transform: uppercase;">Specialized Service Solutions</h2>
                <p class="text-muted" style="max-width: 600px; margin: 15px auto 0;">
                    We offer a complete suite of services from minor maintenance and tyre balance alignments to full high-performance builds.
                </p>
            </div>

            <div class="grid grid-3">
                @foreach($services as $service)
                    <div class="card">
                        <div class="card-body" style="padding: 30px;">
                            <div class="text-primary" style="font-size: 2.2rem; margin-bottom: 15px;">
                                <i class="fa-solid {{ $service->icon }}"></i>
                            </div>
                            <h3 class="card-title" style="font-size: 1.4rem;">{{ $service->title }}</h3>
                            <p class="card-desc" style="font-size: 0.9rem; line-height: 1.5; color: var(--text-muted);">
                                {{ $service->description }}
                            </p>
                            <div class="flex-between" style="border-top: 1px solid var(--border-light); padding-top: 15px; margin-top: 20px;">
                                <span style="font-weight: 700; color: var(--primary); font-size: 1.1rem;">
                                    {{ $service->price ? '$' . number_format($service->price, 2) : 'Custom Quote' }}
                                </span>
                                <a href="{{ route('contact') }}?service={{ $service->slug }}" style="color: var(--text-dark); font-weight: 700; font-size: 0.85rem; text-transform: uppercase;">
                                    Book Now <i class="fa-solid fa-arrow-right-long" style="color: var(--primary); margin-left: 5px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center" style="margin-top: 40px;">
                <a href="{{ route('services') }}" class="btn btn-secondary">
                    View All Services <i class="fa-solid fa-list-ul"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Partner With Us Banner Mockup -->
    <section class="section section-grey scroll-fade" style="border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
        <div class="container">
            <div class="grid grid-2" style="align-items: center; gap: 50px;">
                <div>
                    <span class="hero-subtitle">Join Our Network</span>
                    <h2 style="font-size: 2.8rem; text-transform: uppercase; margin-bottom: 20px;">Partner With TestAutomotive</h2>
                    <p class="text-muted" style="margin-bottom: 30px; font-size: 1.05rem;">
                        Own a shop, sell premium apparel, or blog about riders? Apply for our Authorized reseller scheme to get wholesale pricing on accessories and official graphics.
                    </p>
                    <a href="{{ route('contact') }}?subject=Reseller%20Partnership" class="btn btn-primary">Become a Partner</a>
                </div>
                
                <!-- Styled video mockup card -->
                <div class="card" style="position: relative; overflow: hidden; height: 320px; border-color: var(--border-light); display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(10,10,12,0.95) 100%), url('https://images.unsplash.com/photo-1616422285623-13ff0162193c?w=800') no-repeat center center/cover;">
                    <div style="text-align: center; color: #fff; z-index: 5;">
                        <a href="https://www.youtube.com" target="_blank" style="height: 70px; width: 70px; background-color: var(--primary); color: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 1.8rem; box-shadow: 0 5px 15px rgba(255, 46, 46, 0.4); margin-bottom: 15px; transition: var(--transition);">
                            <i class="fa-solid fa-play" style="margin-left: 5px;"></i>
                        </a>
                        <h4 style="text-transform: uppercase; font-size: 1.3rem; letter-spacing: 0.5px; color:#fff;">Watch Workshop Reel</h4>
                        <span style="font-size: 0.8rem; color: #a1a1aa; text-transform: uppercase; display: block; margin-top: 5px;">Ground Up Bobber Build</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mechanics Highlight Section -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="text-center" style="margin-bottom: 50px;">
                <span class="hero-subtitle">Specialist Hand-crafts</span>
                <h2 style="font-size: 2.8rem; text-transform: uppercase;">Meet Expert Mechanics</h2>
                <p class="text-muted" style="max-width: 600px; margin: 15px auto 0;">
                    We are riders and custom builders first. Trust your motorcycle to the most passionate crew in the country.
                </p>
            </div>

            <div class="grid grid-4">
                @foreach($mechanics as $mechanic)
                    <div class="card text-center" style="padding-top: 30px;">
                        <img src="{{ $mechanic->avatar_path }}" alt="{{ $mechanic->name }}" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; margin: 0 auto 15px; border: 3px solid var(--border-light); box-shadow: var(--shadow);">
                        <div class="card-body" style="padding-top: 10px; padding-bottom: 30px;">
                            <h3 class="card-title" style="font-size: 1.25rem; margin-bottom: 5px;">{{ $mechanic->name }}</h3>
                            <span class="text-primary" style="font-family: var(--font-heading); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; display: block; margin-bottom: 15px;">{{ $mechanic->role }}</span>
                            <div class="flex" style="justify-content: center; flex-wrap: wrap; gap: 5px;">
                                @if(is_array($mechanic->specialties))
                                    @foreach($mechanic->specialties as $spec)
                                        <span style="font-size: 0.72rem; background-color: var(--bg-light); border: 1px solid var(--border-light); padding: 2px 8px; border-radius: 12px; color: var(--text-muted);">{{ $spec }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section section-grey scroll-fade" style="border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
        <div class="container">
            <div class="text-center" style="margin-bottom: 50px;">
                <span class="hero-subtitle">Riders Reviews</span>
                <h2 style="font-size: 2.8rem; text-transform: uppercase;">Customer Testimonials</h2>
            </div>

            <div class="grid grid-3">
                @foreach($testimonials as $testimonial)
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $testimonial->rating ? 'fa-solid' : 'fa-regular' }} fa-star" style="color: var(--primary);"></i>
                            @endfor
                        </div>
                        <p class="testimonial-text">
                            "{{ $testimonial->content }}"
                        </p>
                        <div class="testimonial-author">
                            @if($testimonial->avatar_url)
                                <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" class="testimonial-author-avatar" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div class="testimonial-author-avatar" style="width: 50px; height: 50px; border-radius: 50%; background-color: var(--border-light); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--text-dark); text-transform: uppercase;">
                                    {{ substr($testimonial->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="testimonial-author-name">{{ $testimonial->name }}</div>
                                @if($testimonial->role)
                                    <div class="testimonial-author-role">{{ $testimonial->role }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Help & Support Section (Accordion) -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="grid grid-2" style="align-items: stretch; gap: 50px;">
                <div>
                    <span class="hero-subtitle">Help & Support</span>
                    <h2 style="font-size: 2.8rem; text-transform: uppercase; margin-bottom: 30px;">Frequently Asked Questions</h2>
                    
                    <!-- Alpine-based Accordion -->
                    <div x-data="{ activeAccordion: 1 }">
                        <div class="accordion-item">
                            <div @click="activeAccordion = activeAccordion === 1 ? null : 1" class="accordion-header flex-between">
                                <span>How often should I service my motorcycle?</span>
                                <i :class="activeAccordion === 1 ? 'fa-solid fa-minus' : 'fa-solid fa-plus'"></i>
                            </div>
                            <div x-show="activeAccordion === 1" x-collapse class="accordion-content" style="padding: 0;">
                                <div class="accordion-content-inner" style="padding: 15px 24px 20px;">
                                    Generally, minor services (oil change, chain adjustments) are recommended every 3,000 to 5,000 miles. A full diagnostic and valve clearance tuning is recommended every 12,000 miles or annually.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <div @click="activeAccordion = activeAccordion === 2 ? null : 2" class="accordion-header flex-between">
                                <span>What brands of parts do you use?</span>
                                <i :class="activeAccordion === 2 ? 'fa-solid fa-minus' : 'fa-solid fa-plus'"></i>
                            </div>
                            <div x-show="activeAccordion === 2" x-collapse class="accordion-content" style="padding: 0;">
                                <div class="accordion-content-inner" style="padding: 15px 24px 20px;">
                                    We source only premium OEM replacement parts or performance parts from industry-leading manufacturers, including Öhlins, Brembo, Akrapovič, and Dynojet.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <div @click="activeAccordion = activeAccordion === 3 ? null : 3" class="accordion-header flex-between">
                                <span>Do you offer custom builds or frames?</span>
                                <i :class="activeAccordion === 3 ? 'fa-solid fa-minus' : 'fa-solid fa-plus'"></i>
                            </div>
                            <div x-show="activeAccordion === 3" x-collapse class="accordion-content" style="padding: 0;">
                                <div class="accordion-content-inner" style="padding: 15px 24px 20px;">
                                    Yes, custom fabrication and ground-up builds are at the heart of our garage. Contact us via phone or email to discuss frame modifications, custom subframes, tank shaping, and full design layouts.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="position: relative; min-height: 400px; width: 100%; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-light); box-shadow: var(--shadow);">
                    <img src="https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?w=600" alt="Motorcycle mechanic checking chassis" style="width: 100%; height: 100%; object-fit: cover; transition: all 0.5s ease-in-out;">
                </div>
            </div>

        </div>
    </section>

@endsection
