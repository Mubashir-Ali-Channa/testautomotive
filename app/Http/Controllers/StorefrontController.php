<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Mechanic;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Career;
use App\Models\JobApplication;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    public function home()
    {
        $services = \Illuminate\Support\Facades\Cache::remember('services_all_take6', 3600, function () {
            return Service::where('is_active', true)->take(6)->get();
        });
        $mechanics = \Illuminate\Support\Facades\Cache::remember('mechanics_active_take4', 3600, function () {
            return Mechanic::active()->take(4)->get();
        });
        $featuredProducts = \Illuminate\Support\Facades\Cache::remember('products_featured', 3600, function () {
            return Product::active()->featured()->get();
        });
        $recentPosts = \Illuminate\Support\Facades\Cache::remember('blog_posts_recent', 3600, function () {
            return BlogPost::active()->latest()->take(3)->get();
        });
        $heroSlides = \Illuminate\Support\Facades\Cache::remember('hero_slides_active', 3600, function () {
            return \App\Models\HeroSlide::active()->orderBy('order')->get();
        });
        $testimonials = \Illuminate\Support\Facades\Cache::remember('testimonials_active', 3600, function () {
            return \App\Models\Testimonial::active()->get();
        });
        
        return view('storefront.home', compact('services', 'mechanics', 'featuredProducts', 'recentPosts', 'heroSlides', 'testimonials'));
    }

    public function about()
    {
        return view('storefront.about');
    }

    public function services()
    {
        $services = \Illuminate\Support\Facades\Cache::remember('services_all', 3600, function () {
            return Service::where('is_active', true)->get();
        });
        return view('storefront.services', compact('services'));
    }

    public function mechanics()
    {
        $mechanics = \Illuminate\Support\Facades\Cache::remember('mechanics_active', 3600, function () {
            return Mechanic::active()->get();
        });
        return view('storefront.mechanics', compact('mechanics'));
    }

    public function locations()
    {
        return view('storefront.locations');
    }

    public function careers()
    {
        $careers = \Illuminate\Support\Facades\Cache::remember('careers_active', 3600, function () {
            return Career::active()->get();
        });
        return view('storefront.careers', compact('careers'));
    }

    public function applyJob(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:4096',
            'message' => 'nullable|string',
        ]);

        $career = Career::findOrFail($id);

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
        } else {
            return back()->with('error', 'Resume upload failed.');
        }

        \App\Jobs\ProcessJobApplication::dispatch([
            'career_id' => $career->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'resume_path' => $path,
            'message' => $request->message,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your application for ' . $career->title . ' has been submitted successfully!'
            ]);
        }

        return back()->with('success', 'Your application for ' . $career->title . ' has been submitted successfully!');
    }

    public function contact()
    {
        return view('storefront.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => ['required', 'string', function ($attribute, $value, $fail) {
                if (str_word_count($value) > 100) {
                    $fail('The subject must not exceed 100 words.');
                }
            }],
            'message' => ['required', 'string', function ($attribute, $value, $fail) {
                if (str_word_count($value) > 200) {
                    $fail('The message must not exceed 200 words.');
                }
            }],
        ]);

        \App\Jobs\ProcessContactMessage::dispatch([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Your message has been sent successfully. We will get back to you soon!');
    }

    public function blog()
    {
        $posts = BlogPost::active()->latest()->paginate(6);
        return view('storefront.blog', compact('posts'));
    }

    public function blogPost($slug)
    {
        $post = BlogPost::active()->where('slug', $slug)->firstOrFail();
        $recentPosts = BlogPost::active()->where('id', '!=', $post->id)->latest()->take(5)->get();
        return view('storefront.blog_detail', compact('post', 'recentPosts'));
    }
}
