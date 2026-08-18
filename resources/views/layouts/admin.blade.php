<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TestAutomotive</title>
    
    <!-- Fonts and FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/testautomotive.css') }}">
    
    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Alpine.js Collapse Plugin (Load before Alpine core) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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

    <div x-data="{ mobileMenuOpen: false }">
        <!-- Mobile Top Navigation Header -->
        <div class="admin-mobile-header flex-between" style="display: none; padding: 15px 20px; background-color: var(--bg-dark); color: #fff; position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid var(--border-dark);">
            <div style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; text-transform: uppercase;">
                TEST<span style="color: var(--primary);">AUTO</span>
            </div>
            <button @click="mobileMenuOpen = !mobileMenuOpen" style="background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; padding: 5px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Sidebar Backdrop Overlay on Mobile -->
        <div x-show="mobileMenuOpen" 
             @click="mobileMenuOpen = false" 
             x-transition.opacity 
             style="position: fixed; top: 60px; left: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); z-index: 998;"
             class="admin-sidebar-backdrop">
        </div>

        <div id="dashboard-preloader" class="preloader">
            <div class="spinner"></div>
        </div>

        <div class="admin-layout">
            
            <!-- Sidebar Navigation -->
            <aside class="admin-sidebar" :class="{ 'open': mobileMenuOpen }">
            <div class="admin-sidebar-logo">
                <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--text-light); text-transform: uppercase;">
                    TEST<span style="color: var(--primary);">AUTOMOTIVE</span>
                </div>
                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px; display: block; margin-top: 3px;">Control Panel</span>
            </div>

            <ul class="admin-sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products') }}" class="admin-sidebar-link {{ Route::is('admin.products') || Route::is('admin.products.create') || Route::is('admin.products.edit') ? 'active' : '' }}">
                        <i class="fa-solid fa-box"></i> Products Catalog
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders') }}" class="admin-sidebar-link {{ Route::is('admin.orders') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt"></i> Customer Orders
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.services') }}" class="admin-sidebar-link {{ Route::is('admin.services') ? 'active' : '' }}">
                        <i class="fa-solid fa-wrench"></i> Shop Services
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.mechanics') }}" class="admin-sidebar-link {{ Route::is('admin.mechanics') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear"></i> Workshop Staff
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.blogs') }}" class="admin-sidebar-link {{ Route::is('admin.blogs') ? 'active' : '' }}">
                        <i class="fa-solid fa-newspaper"></i> Garage Blog
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.careers') }}" class="admin-sidebar-link {{ Route::is('admin.careers') ? 'active' : '' }}">
                        <i class="fa-solid fa-briefcase"></i> Careers CMS
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.leads') }}" class="admin-sidebar-link {{ Route::is('admin.leads') ? 'active' : '' }}">
                        <i class="fa-solid fa-inbox"></i> Leads Inbox
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hero_slides') }}" class="admin-sidebar-link {{ Route::is('admin.hero_slides') ? 'active' : '' }}">
                        <i class="fa-solid fa-images"></i> Hero Slider CMS
                    </a>
                </li>
                @if(auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('admin.admins') }}" class="admin-sidebar-link {{ Route::is('admin.admins') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-shield"></i> Admins Manager
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('admin.reviews') }}" class="admin-sidebar-link {{ Route::is('admin.reviews') ? 'active' : '' }}">
                        <i class="fa-solid fa-star"></i> Reviews Moderator
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.testimonials') }}" class="admin-sidebar-link {{ Route::is('admin.testimonials') ? 'active' : '' }}">
                        <i class="fa-solid fa-comments"></i> Testimonials CMS
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings') }}" class="admin-sidebar-link {{ Route::is('admin.settings') ? 'active' : '' }}">
                        <i class="fa-solid fa-gears"></i> General Settings
                    </a>
                </li>
                
                <li style="margin-top: 50px; border-top: 1px solid var(--border-dark); padding-top: 20px;">
                    <a href="{{ route('home') }}" target="_blank" class="admin-sidebar-link" style="color: var(--text-light); display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <span>Store Front</span>
                        <i class="fa-solid fa-up-right-from-square" style="font-size: 0.85rem; margin-left: 8px;"></i>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content Pane -->
        <main class="admin-main">
            
            <header class="admin-header flex-between" style="border-bottom: 1px solid var(--border-light); padding-bottom: 20px;">
                <div>
                    <span style="font-size:0.9rem; text-transform:uppercase; color: var(--text-muted);">Welcome back</span>
                    <h2 style="font-size:1.8rem; text-transform:uppercase; margin-top:2px;">{{ auth()->user()->name }}</h2>
                </div>
                <div class="flex" style="gap: 15px;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="padding: 10px 20px; font-size: 0.95rem;">
                            Logout <i class="fa-solid fa-sign-out-alt" style="margin-left: 5px;"></i>
                        </button>
                    </form>
                </div>
            </header>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts



    @yield('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var pre = document.getElementById('dashboard-preloader');
        if (pre) {
            pre.classList.add('hidden');
        }
    });
</script>
</body>
</html>
