<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'TestAutomotive is the premier choice for custom auto design, advanced mechanical tuning, and premium parts.')">
    <meta name="keywords" content="@yield('meta_keywords', 'automotive, tuning, custom vehicle, diagnostics, auto shop, parts, TestAutomotive')">
    <title>@yield('title', 'TestAutomotive') | Automotive Specialist Shop</title>
    
    <!-- Fonts and FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/testautomotive.css') }}">
    
    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Alpine.js Collapse Plugin (Load before Alpine core) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    
    @yield('styles')
</head>
<body>

    <!-- Global Alpine Toast Container -->
    <div x-data="{ toasts: [] }" 
         @toast.window="let id = Date.now(); toasts.push({ id: id, message: $event.detail.message, status: $event.detail.status }); setTimeout(() => { toasts = toasts.filter(t => t.id !== id) }, 3000)"
         class="toast-container"
         x-init="
            @if(session('success'))
                setTimeout(() => { $dispatch('toast', { message: '{{ session('success') }}', status: 'success' }) }, 150);
            @endif
            @if(session('error'))
                setTimeout(() => { $dispatch('toast', { message: '{{ session('error') }}', status: 'error' }) }, 150);
            @endif
            @if(session('success_order'))
                setTimeout(() => { $dispatch('toast', { message: '{{ session('success_order') }}', status: 'success' }) }, 150);
            @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    setTimeout(() => { $dispatch('toast', { message: '{{ addslashes($error) }}', status: 'error' }) }, 200);
                @endforeach
            @endif
         ">
        <template x-for="t in toasts" :key="t.id">
            <div :class="'toast-message toast-' + t.status">
                <template x-if="t.status === 'success'">
                    <i class="fa-solid fa-circle-check" style="color: var(--success); font-size: 1.2rem;"></i>
                </template>
                <template x-if="t.status !== 'success'">
                    <i class="fa-solid fa-circle-exclamation" style="color: var(--danger); font-size: 1.2rem;"></i>
                </template>
                <span x-text="t.message" style="font-weight: 600;"></span>
            </div>
        </template>
    </div>

    <!-- Header Navigation -->
    <header class="site-header scroll-fade">
        <div class="container nav-bar">
            <a href="{{ route('home') }}" class="logo">
                <div>TEST<span style="color: var(--primary);">AUTOMOTIVE</span></div>
            </a>

            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('home') }}" class="nav-link {{ Route::is('home') ? 'active' : '' }}">Home</a></li>
                    
                    <!-- Workshop Dropdown Menu -->
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ Route::is('about') || Route::is('services') || Route::is('mechanics') || Route::is('locations') || Route::is('careers') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 5px;">
                            Workshop <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem;"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('about') }}" class="dropdown-item">About Us</a></li>
                            <li><a href="{{ route('services') }}" class="dropdown-item">Our Services</a></li>
                            <li><a href="{{ route('mechanics') }}" class="dropdown-item">Specialist Staff</a></li>
                            <li><a href="{{ route('locations') }}" class="dropdown-item">Locations</a></li>
                            <li><a href="{{ route('careers') }}" class="dropdown-item">Careers</a></li>
                        </ul>
                    </li>

                    <li><a href="{{ route('shop') }}" class="nav-link {{ Route::is('shop') || Route::is('product.detail') ? 'active' : '' }}">Shop</a></li>
                    <li><a href="{{ route('blog') }}" class="nav-link {{ Route::is('blog') || Route::is('blog.post') ? 'active' : '' }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-link {{ Route::is('contact') ? 'active' : '' }}">Contact</a></li>
                </ul>
            </nav>

            <div class="nav-actions">
                <!-- Reactive Livewire Cart Badge -->
                <livewire:header-cart-badge />

                @auth
                    <!-- Profile Dropdown -->
                    <div class="nav-item" x-data="{ open: false }" @click.away="open = false" style="position: relative;">
                        <button type="button" @click="open = !open" class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; text-transform: uppercase;">
                            <i class="fa-solid fa-user"></i> {{ auth()->user()->name }} <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem;"></i>
                        </button>
                        <ul class="dropdown-menu" 
                            x-show="open" 
                            x-transition
                            style="display: none; position: absolute; top: 100%; right: 0; left: auto; background-color: var(--bg-white); border: 1px solid var(--border-light); border-radius: 6px; box-shadow: var(--shadow-hover); list-style: none; min-width: 180px; z-index: 1010; padding: 8px 0; opacity: 1; visibility: visible; transform: translateY(0);">
                            @if(auth()->user()->isAdmin())
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 10px 20px; font-size: 0.95rem; font-family: var(--font-heading); font-weight: 600; text-transform: uppercase; color: var(--text-dark);">
                                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('my-account') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 10px 20px; font-size: 0.95rem; font-family: var(--font-heading); font-weight: 600; text-transform: uppercase; color: var(--text-dark);">
                                    <i class="fa-solid fa-user-gear"></i> My Account
                                </a>
                            </li>
                            <li style="border-top: 1px solid var(--border-light); margin-top: 5px; padding-top: 5px;">
                                <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; font-family: var(--font-heading); font-weight: 600; text-transform: uppercase; color: var(--text-dark); display: flex; align-items: center; gap: 10px; padding: 10px 20px; font-size: 0.95rem;">
                                        <i class="fa-solid fa-sign-out-alt" style="color: var(--primary);"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 8px 18px; font-size: 0.95rem;">
                        <i class="fa-solid fa-user-lock"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main id="content">
        @yield('content')
    </main>

    <!-- Footer Area (Dark background) -->
    <footer class="site-footer scroll-fade">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4 class="footer-widget-title">TESTAUTOMOTIVE</h4>
                    <p style="color: #a1a1aa; font-size: 0.95rem; margin-bottom: 20px;">
                        More than just a shop—we are a dedicated team of custom builders, riders, and mechanics who live and breathe two wheels.
                    </p>
                    <div class="flex" style="gap: 15px; font-size: 1.2rem;">
                        <a href="#" style="color: #a1a1aa;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" style="color: #a1a1aa;"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" style="color: #a1a1aa;"><i class="fa-brands fa-x-twitter"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-widget-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('services') }}">Our Services</a></li>
                        <li><a href="{{ route('mechanics') }}">Meet Mechanics</a></li>
                        <li><a href="{{ route('shop') }}">Shop Parts & Bikes</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-widget-title">Help & Info</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('careers') }}">Careers / Jobs</a></li>
                        <li><a href="{{ route('locations') }}">Shop Locations</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('cart') }}">View Cart</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-widget-title">Newsletter</h4>
                    <p style="color: #a1a1aa; font-size: 0.95rem; margin-bottom: 15px;">
                        Subscribe to get latest custom build announcements and gear deals.
                    </p>
                    <form action="#" method="POST" style="display: flex; gap: 5px;" onsubmit="alert('Subscribed to newsletter!'); return false;">
                        <input type="email" placeholder="Your Email" required class="form-control" style="padding: 10px; background-color: var(--border-dark); border-color: var(--border-dark); color: #fff;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 15px;">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} TestAutomotive. Created in Laravel. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Viewport Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.scroll-fade');
            if (fadeElements.length > 0) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.05,
                    rootMargin: '0px 0px -40px 0px'
                });
                fadeElements.forEach(el => observer.observe(el));
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
