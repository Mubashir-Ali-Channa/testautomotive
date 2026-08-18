<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Service;
use App\Models\Mechanic;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'revenue' => Order::where('status', 'completed')->sum('total'),
            'orders_count' => Order::count(),
            'products_count' => Product::count(),
            'messages_count' => ContactMessage::count(),
            'applications_count' => JobApplication::count(),
        ];

        // Monthly Revenue (Last 6 months)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthlyData = Order::where('status', 'completed')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, SUM(total) as revenue')
            ->groupBy('month')
            ->orderBy('created_at', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthLabel = now()->subMonths($i)->format('M Y');
            $chartLabels[] = $monthLabel;
            $found = $monthlyData->first(fn($item) => $item->month === $monthLabel);
            $chartData[] = $found ? (float)$found->revenue : 0.0;
        }

        // Order Status Distribution
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $statusLabels = ['pending', 'processing', 'completed', 'cancelled'];
        $statusData = [];
        foreach ($statusLabels as $status) {
            $statusData[] = isset($ordersByStatus[$status]) ? $ordersByStatus[$status] : 0;
        }

        $recentOrders = Order::latest()->take(5)->get();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'chartLabels',
            'chartData',
            'statusLabels',
            'statusData',
            'recentOrders',
            'recentMessages'
        ));
    }

    // --- PRODUCTS CMS ---
    public function products()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'spec_keys.*' => 'nullable|string',
            'spec_values.*' => 'nullable|string',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = asset('storage/' . $file->store('products', 'public'));
            }
        } else {
            // Default placeholder
            $imagePaths[] = 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800';
        }

        // Process specifications
        $specifications = [];
        $specKeys = $request->input('spec_keys', []);
        $specValues = $request->input('spec_values', []);
        foreach ($specKeys as $index => $key) {
            if (!empty($key) && isset($specValues[$index])) {
                $specifications[$key] = $specValues[$index];
            }
        }

        Product::create([
            'name' => strip_tags($request->name),
            'slug' => Str::slug($request->name) . '-' . rand(100, 999),
            'description' => strip_tags($request->description),
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => strip_tags($request->category),
            'image_paths' => $imagePaths,
            'specifications' => array_map('strip_tags', $specifications),
            'is_featured' => $request->has('is_featured'),
        ]);

        \Illuminate\Support\Facades\Cache::forget('products_featured');

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'spec_keys.*' => 'nullable|string',
            'spec_values.*' => 'nullable|string',
        ]);

        $imagePaths = $product->image_paths ?? [];
        if ($request->hasFile('images')) {
            $imagePaths = []; // Overwrite existing if new uploaded
            foreach ($request->file('images') as $file) {
                $imagePaths[] = asset('storage/' . $file->store('products', 'public'));
            }
        }

        // Process specifications
        $specifications = [];
        $specKeys = $request->input('spec_keys', []);
        $specValues = $request->input('spec_values', []);
        foreach ($specKeys as $index => $key) {
            if (!empty($key) && isset($specValues[$index])) {
                $specifications[$key] = $specValues[$index];
            }
        }

        $product->update([
            'name' => strip_tags($request->name),
            'slug' => Str::slug($request->name) . '-' . $product->id,
            'description' => strip_tags($request->description),
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => strip_tags($request->category),
            'image_paths' => $imagePaths,
            'specifications' => array_map('strip_tags', $specifications),
            'is_featured' => $request->has('is_featured'),
        ]);

        \Illuminate\Support\Facades\Cache::forget('products_featured');

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        \Illuminate\Support\Facades\Cache::forget('products_featured');
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    // --- ORDERS CMS ---
    public function orders()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['status' => 'required|string']);
        $order->status = $request->status;
        $order->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.'
            ]);
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    // --- SERVICES CMS ---
    public function services()
    {
        $services = Service::latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string|max:255',
        ]);

        Service::create([
            'title' => strip_tags($request->title),
            'slug' => Str::slug($request->title),
            'description' => strip_tags($request->description),
            'price' => $request->price,
            'icon' => strip_tags($request->icon) ?? 'fa-cog',
        ]);

        \Illuminate\Support\Facades\Cache::forget('services_all');

        return back()->with('success', 'Service created successfully.');
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string|max:255',
        ]);

        $service->update([
            'title' => strip_tags($request->title),
            'slug' => Str::slug($request->title),
            'description' => strip_tags($request->description),
            'price' => $request->price,
            'icon' => strip_tags($request->icon) ?? 'fa-cog',
        ]);

        \Illuminate\Support\Facades\Cache::forget('services_all');

        return back()->with('success', 'Service updated successfully.');
    }

    public function destroyService($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        \Illuminate\Support\Facades\Cache::forget('services_all');
        \Illuminate\Support\Facades\Cache::forget('services_all_take6');
        return back()->with('success', 'Service deleted successfully.');
    }

    public function toggleServiceActive($id)
    {
        $service = Service::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        \Illuminate\Support\Facades\Cache::forget('services_all');
        \Illuminate\Support\Facades\Cache::forget('services_all_take6');

        return response()->json(['success' => true]);
    }

    // --- MECHANICS CMS ---
    public function mechanics()
    {
        $mechanics = Mechanic::all();
        return view('admin.mechanics.index', compact('mechanics'));
    }

    public function storeMechanic(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'specialties' => 'required|string',
        ]);

        $avatarPath = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500';
        if ($request->hasFile('avatar')) {
            $avatarPath = asset('storage/' . $request->file('avatar')->store('mechanics', 'public'));
        }

        $specialtiesArray = array_map('trim', explode(',', $request->specialties));

        Mechanic::create([
            'name' => strip_tags($request->name),
            'role' => strip_tags($request->role),
            'bio' => strip_tags($request->bio),
            'avatar_path' => $avatarPath,
            'specialties' => array_map('strip_tags', $specialtiesArray),
        ]);

        \Illuminate\Support\Facades\Cache::forget('mechanics_active');

        return back()->with('success', 'Mechanic added successfully.');
    }

    public function updateMechanic(Request $request, $id)
    {
        $mechanic = Mechanic::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'specialties' => 'required|string',
        ]);

        $avatarPath = $mechanic->avatar_path;
        if ($request->hasFile('avatar')) {
            $avatarPath = asset('storage/' . $request->file('avatar')->store('mechanics', 'public'));
        }

        $specialtiesArray = array_map('trim', explode(',', $request->specialties));

        $mechanic->update([
            'name' => strip_tags($request->name),
            'role' => strip_tags($request->role),
            'bio' => strip_tags($request->bio),
            'avatar_path' => $avatarPath,
            'specialties' => array_map('strip_tags', $specialtiesArray),
        ]);

        \Illuminate\Support\Facades\Cache::forget('mechanics_active');

        return back()->with('success', 'Mechanic updated successfully.');
    }

    public function destroyMechanic($id)
    {
        $mechanic = Mechanic::findOrFail($id);
        $mechanic->delete();
        \Illuminate\Support\Facades\Cache::forget('mechanics_active');
        \Illuminate\Support\Facades\Cache::forget('mechanics_active_take4');
        return back()->with('success', 'Mechanic deleted successfully.');
    }

    // --- BLOGS CMS ---
    public function blogs()
    {
        $blogs = BlogPost::latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800';
        if ($request->hasFile('image')) {
            $imagePath = asset('storage/' . $request->file('image')->store('blogs', 'public'));
        }

        BlogPost::create([
            'title' => strip_tags($request->title),
            'slug' => Str::slug($request->title) . '-' . rand(10, 99),
            'content' => strip_tags($request->content),
            'category' => strip_tags($request->category),
            'image_path' => $imagePath,
        ]);

        \Illuminate\Support\Facades\Cache::forget('blog_posts_recent');

        return back()->with('success', 'Blog article created successfully.');
    }

    public function updateBlog(Request $request, $id)
    {
        $blog = BlogPost::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $blog->image_path;
        if ($request->hasFile('image')) {
            $imagePath = asset('storage/' . $request->file('image')->store('blogs', 'public'));
        }

        $blog->update([
            'title' => strip_tags($request->title),
            'slug' => Str::slug($request->title) . '-' . $blog->id,
            'content' => strip_tags($request->content),
            'category' => strip_tags($request->category),
            'image_path' => $imagePath,
        ]);

        \Illuminate\Support\Facades\Cache::forget('blog_posts_recent');

        return back()->with('success', 'Blog article updated successfully.');
    }

    public function destroyBlog($id)
    {
        $blog = BlogPost::findOrFail($id);
        $blog->delete();
        \Illuminate\Support\Facades\Cache::forget('blog_posts_recent');
        return back()->with('success', 'Blog article deleted successfully.');
    }

    // --- LEADS INBOX ---
    public function leads()
    {
        $messages = ContactMessage::latest()->get();
        $applications = JobApplication::with('career')->latest()->get();
        return view('admin.leads.index', compact('messages', 'applications'));
    }

    // --- SETTINGS CMS ---
    public function settings()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }
        return back()->with('success', 'Global settings updated successfully.');
    }

    // --- HERO SLIDES CMS ---
    public function heroSlides()
    {
        $slides = \App\Models\HeroSlide::orderBy('order')->get();
        return view('admin.hero_slides.index', compact('slides'));
    }

    public function storeHeroSlide(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:4096',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $imagePath = 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=1600';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('slides', 'public');
            $imagePath = asset('storage/' . $path);
        }

        \App\Models\HeroSlide::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $imagePath,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'order' => $request->order,
        ]);

        return back()->with('success', 'Hero slide added successfully.');
    }

    public function updateHeroSlide(Request $request, $id)
    {
        $slide = \App\Models\HeroSlide::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:4096',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('slides', 'public');
            $slide->image_path = asset('storage/' . $path);
        }

        $slide->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'order' => $request->order,
        ]);

        return back()->with('success', 'Hero slide updated successfully.');
    }

    public function destroyHeroSlide($id)
    {
        $slide = \App\Models\HeroSlide::findOrFail($id);
        $slide->delete();
        \Illuminate\Support\Facades\Cache::forget('hero_slides_active');
        return back()->with('success', 'Hero slide deleted successfully.');
    }

    // --- ADMIN PROFILE CREATION ---
    public function adminsList()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        $admins = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'admin',
        ]);

        return back()->with('success', 'Admin profile created successfully.');
    }

    public function destroyAdmin($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        $user = \App\Models\User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own admin account.');
        }

        if ($user->role === 'super_admin') {
            return back()->with('error', 'Cannot delete the Super Admin profile.');
        }

        $user->delete();
        return back()->with('success', 'Admin account deleted successfully.');
    }

    public function toggleAdminActive($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        $user = \App\Models\User::findOrFail($id);

        if ($user->role === 'super_admin') {
            return response()->json(['error' => 'Cannot deactivate the Super Admin profile.'], 400);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json(['success' => true]);
    }

    // --- STATUS TOGGLES ---
    public function toggleProductFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();
        \Illuminate\Support\Facades\Cache::forget('products_featured');
        return response()->json(['success' => true]);
    }

    public function toggleProductActive($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();
        \Illuminate\Support\Facades\Cache::forget('products_featured');
        return response()->json(['success' => true]);
    }

    public function toggleHeroSlideActive($id)
    {
        $slide = \App\Models\HeroSlide::findOrFail($id);
        $slide->is_active = !$slide->is_active;
        $slide->save();
        \Illuminate\Support\Facades\Cache::forget('hero_slides_active');
        return back()->with('success', 'Hero slide active status updated.');
    }
    public function toggleMechanicActive($id)
    {
        $mechanic = Mechanic::findOrFail($id);
        $mechanic->is_active = !$mechanic->is_active;
        $mechanic->save();
        \Illuminate\Support\Facades\Cache::forget('mechanics_active');
        \Illuminate\Support\Facades\Cache::forget('mechanics_active_take4');
        return back()->with('success', 'Mechanic active status updated.');
    }

    public function toggleBlogActive($id)
    {
        $blog = \App\Models\BlogPost::findOrFail($id);
        $blog->is_active = !$blog->is_active;
        $blog->save();
        \Illuminate\Support\Facades\Cache::forget('blog_posts_recent');
        return back()->with('success', 'Blog active status updated.');
    }

    // --- REVIEWS CMS ---
    public function reviewsList()
    {
        $pendingReviews = \App\Models\Review::where('is_approved', false)->with(['product', 'user'])->latest()->get();
        $approvedReviews = \App\Models\Review::where('is_approved', true)->with(['product', 'user'])->latest()->get();
        return view('admin.reviews.index', compact('pendingReviews', 'approvedReviews'));
    }

    public function approveReview($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->is_approved = true;
        $review->save();
        return back()->with('success', 'Review approved successfully.');
    }

    public function destroyReview($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }

    // --- TESTIMONIALS CMS ---
    public function testimonials()
    {
        $testimonials = \App\Models\Testimonial::latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'avatar_url' => 'nullable|url|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['name'] = strip_tags($validated['name']);
        $validated['role'] = strip_tags($validated['role'] ?? '');
        $validated['content'] = strip_tags($validated['content']);

        \App\Models\Testimonial::create($validated);

        \Illuminate\Support\Facades\Cache::forget('testimonials_active');

        return back()->with('success', 'Customer testimonial added successfully.');
    }

    public function toggleTestimonialActive($id)
    {
        $testimonial = \App\Models\Testimonial::findOrFail($id);
        $testimonial->is_active = !$testimonial->is_active;
        $testimonial->save();
        \Illuminate\Support\Facades\Cache::forget('testimonials_active');
        return back()->with('success', 'Testimonial active status updated.');
    }

    public function destroyTestimonial($id)
    {
        $testimonial = \App\Models\Testimonial::findOrFail($id);
        $testimonial->delete();
        \Illuminate\Support\Facades\Cache::forget('testimonials_active');
        return back()->with('success', 'Testimonial deleted successfully.');
    }

    // --- CAREERS CMS ---
    public function careersList()
    {
        $careers = \App\Models\Career::latest()->paginate(10);
        return view('admin.careers.index', compact('careers'));
    }

    public function storeCareer(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        $validated['title'] = strip_tags($validated['title']);
        $validated['department'] = strip_tags($validated['department']);
        $validated['type'] = strip_tags($validated['type']);
        $validated['description'] = strip_tags($validated['description']);
        $validated['requirements'] = strip_tags($validated['requirements']);

        \App\Models\Career::create($validated);

        \Illuminate\Support\Facades\Cache::forget('careers_active');

        return back()->with('success', 'Career posting published successfully.');
    }

    public function updateCareer(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        $career = \App\Models\Career::findOrFail($id);

        $validated['title'] = strip_tags($validated['title']);
        $validated['department'] = strip_tags($validated['department']);
        $validated['type'] = strip_tags($validated['type']);
        $validated['description'] = strip_tags($validated['description']);
        $validated['requirements'] = strip_tags($validated['requirements']);

        $career->update($validated);

        \Illuminate\Support\Facades\Cache::forget('careers_active');

        return back()->with('success', 'Career posting updated successfully.');
    }

    public function destroyCareer($id)
    {
        $career = \App\Models\Career::findOrFail($id);
        $career->delete();
        \Illuminate\Support\Facades\Cache::forget('careers_active');
        return back()->with('success', 'Career posting deleted successfully.');
    }

    public function toggleCareerActive($id)
    {
        $career = \App\Models\Career::findOrFail($id);
        $career->is_active = !$career->is_active;
        $career->save();
        \Illuminate\Support\Facades\Cache::forget('careers_active');
        return back()->with('success', 'Career posting active status updated.');
    }

    public function viewResume($id)
    {
        $application = \App\Models\JobApplication::findOrFail($id);
        return view('admin.leads.resume', compact('application'));
    }
}
