<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StorefrontController;
use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;

// Global throttled routes group
Route::middleware(['throttle:global'])->group(function () {
    // Storefront routes
    Route::get('/', [StorefrontController::class, 'home'])->name('home');
    Route::get('/about', [StorefrontController::class, 'about'])->name('about');
    Route::get('/services', [StorefrontController::class, 'services'])->name('services');
    Route::get('/mechanics', [StorefrontController::class, 'mechanics'])->name('mechanics');
    Route::get('/locations', [StorefrontController::class, 'locations'])->name('locations');
    Route::get('/careers', [StorefrontController::class, 'careers'])->name('careers');
    Route::get('/contact', [StorefrontController::class, 'contact'])->name('contact');
    Route::get('/blog', [StorefrontController::class, 'blog'])->name('blog');
    Route::get('/blog/{slug}', [StorefrontController::class, 'blogPost'])->name('blog.post');

    // High load form submissions throttled to 'form' rate limiter (3 per min)
    Route::post('/careers/{id}/apply', [StorefrontController::class, 'applyJob'])->middleware('throttle:form')->name('careers.apply');
    Route::post('/contact', [StorefrontController::class, 'submitContact'])->middleware('throttle:form')->name('contact.submit');

    // Shop & Cart routes
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/product/{slug}', [ShopController::class, 'product'])->name('product.detail');
    Route::get('/cart', [ShopController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{id}', [ShopController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [ShopController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove/{id}', [ShopController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [ShopController::class, 'submitCheckout'])->middleware('throttle:form')->name('checkout.submit');

    // Authenticated customer account route
    Route::middleware(['auth'])->group(function () {
        Route::get('/my-account', [ShopController::class, 'myAccount'])->name('my-account');
    });

    // Authentication routes (limit to 5 attempts per minute)
    Route::middleware(['throttle:auth'])->group(function () {
        Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/admin/login', [AuthController::class, 'adminLoginForm'])->name('admin.login');
        Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', AdminCheck::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Products CMS
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::post('/products/{id}/delete', [AdminController::class, 'destroyProduct'])->name('admin.products.delete');
    Route::post('/products/{id}/toggle-featured', [AdminController::class, 'toggleProductFeatured'])->name('admin.products.toggle_featured');
    Route::post('/products/{id}/toggle-active', [AdminController::class, 'toggleProductActive'])->name('admin.products.toggle_active');

    // Orders CMS
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');

    // Services CMS
    Route::get('/services', [AdminController::class, 'services'])->name('admin.services');
    Route::post('/services', [AdminController::class, 'storeService'])->name('admin.services.store');
    Route::post('/services/{id}', [AdminController::class, 'updateService'])->name('admin.services.update');
    Route::post('/services/{id}/delete', [AdminController::class, 'destroyService'])->name('admin.services.delete');
    Route::post('/services/{id}/toggle-active', [AdminController::class, 'toggleServiceActive'])->name('admin.services.toggle_active');

    // Mechanics CMS
    Route::get('/mechanics', [AdminController::class, 'mechanics'])->name('admin.mechanics');
    Route::post('/mechanics', [AdminController::class, 'storeMechanic'])->name('admin.mechanics.store');
    Route::post('/mechanics/{id}', [AdminController::class, 'updateMechanic'])->name('admin.mechanics.update');
    Route::post('/mechanics/{id}/delete', [AdminController::class, 'destroyMechanic'])->name('admin.mechanics.delete');
    Route::post('/mechanics/{id}/toggle-active', [AdminController::class, 'toggleMechanicActive'])->name('admin.mechanics.toggle_active');

    // Blogs CMS
    Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
    Route::post('/blogs', [AdminController::class, 'storeBlog'])->name('admin.blogs.store');
    Route::post('/blogs/{id}', [AdminController::class, 'updateBlog'])->name('admin.blogs.update');
    Route::post('/blogs/{id}/delete', [AdminController::class, 'destroyBlog'])->name('admin.blogs.delete');
    Route::post('/blogs/{id}/toggle-active', [AdminController::class, 'toggleBlogActive'])->name('admin.blogs.toggle_active');

    // Careers CMS
    Route::get('/careers', [AdminController::class, 'careersList'])->name('admin.careers');
    Route::post('/careers', [AdminController::class, 'storeCareer'])->name('admin.careers.store');
    Route::post('/careers/{id}', [AdminController::class, 'updateCareer'])->name('admin.careers.update');
    Route::post('/careers/{id}/delete', [AdminController::class, 'destroyCareer'])->name('admin.careers.delete');
    Route::post('/careers/{id}/toggle-active', [AdminController::class, 'toggleCareerActive'])->name('admin.careers.toggle_active');

    // Leads CMS (Contact Messages & Career Applications)
    Route::get('/leads', [AdminController::class, 'leads'])->name('admin.leads');
    Route::get('/leads/resume/{id}', [AdminController::class, 'viewResume'])->name('admin.leads.resume');

    // Hero Slides CMS
    Route::get('/hero-slides', [AdminController::class, 'heroSlides'])->name('admin.hero_slides');
    Route::post('/hero-slides', [AdminController::class, 'storeHeroSlide'])->name('admin.hero_slides.store');
    Route::post('/hero-slides/{id}', [AdminController::class, 'updateHeroSlide'])->name('admin.hero_slides.update');
    Route::post('/hero-slides/{id}/delete', [AdminController::class, 'destroyHeroSlide'])->name('admin.hero_slides.delete');
    Route::post('/hero-slides/{id}/toggle-active', [AdminController::class, 'toggleHeroSlideActive'])->name('admin.hero_slides.toggle_active');

    // Settings CMS
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // Reviews approval manager
    Route::get('/reviews', [AdminController::class, 'reviewsList'])->name('admin.reviews');
    Route::post('/reviews/{id}/approve', [AdminController::class, 'approveReview'])->name('admin.reviews.approve');
    Route::post('/reviews/{id}/delete', [AdminController::class, 'destroyReview'])->name('admin.reviews.delete');

    // Admin management (Super Admin only - check inside controller methods)
    Route::get('/admins', [AdminController::class, 'adminsList'])->name('admin.admins');
    Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admin.admins.store');
    Route::post('/admins/{id}/delete', [AdminController::class, 'destroyAdmin'])->name('admin.admins.delete');
    Route::post('/admins/{id}/toggle-active', [AdminController::class, 'toggleAdminActive'])->name('admin.admins.toggle_active');

    // Testimonials CMS
    Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::post('/testimonials', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::post('/testimonials/{id}/toggle-active', [AdminController::class, 'toggleTestimonialActive'])->name('admin.testimonials.toggle_active');
    Route::post('/testimonials/{id}/delete', [AdminController::class, 'destroyTestimonial'])->name('admin.testimonials.delete');
});
