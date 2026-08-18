@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', Str::limit(strip_tags($post->content), 150))

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 30%;">
        <div class="container text-center">
            <span class="hero-subtitle">{{ $post->category }}</span>
            <h1 class="hero-title" style="font-size: 3rem; margin-top: 10px; max-width: 900px; margin-left: auto; margin-right: auto; line-height: 1.2;">{{ $post->title }}</h1>
            <p style="color: var(--text-muted); margin-top: 15px;">Published on {{ $post->created_at->format('M d, Y') }}</p>
        </div>
    </section>

    <!-- Post Body & Sidebar -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 50px;">
                
                <!-- Main Body -->
                <div>
                    <img src="{{ $post->image_path }}" alt="{{ $post->title }}" style="width: 100%; height: 400px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-light); margin-bottom: 30px;">
                    
                    <div style="font-size: 1.1rem; line-height: 1.8; color: var(--text-dark); white-space: pre-line;">
                        {{ $post->content }}
                    </div>

                    <div style="margin-top: 50px; border-top: 1px solid var(--border-light); padding-top: 30px; display: flex; align-items: center; justify-content: space-between;">
                        <a href="{{ route('blog') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Back to Blog
                        </a>
                        <div class="flex" style="gap: 10px;">
                            <span>Share:</span>
                            <a href="#" style="color: var(--text-muted);"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" style="color: var(--text-muted);"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div>
                    <div class="card" style="padding: 30px; margin-bottom: 30px; position: sticky; top: 100px;">
                        <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid var(--primary); padding-bottom: 8px;">Recent Articles</h3>
                        
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            @foreach($recentPosts as $recent)
                                <div class="flex" style="align-items: flex-start; gap: 15px;">
                                    <img src="{{ $recent->image_path }}" alt="{{ $recent->title }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-light);">
                                    <div>
                                        <h4 style="font-size: 0.95rem; text-transform: uppercase; line-height: 1.3; margin-bottom: 3px;">
                                            <a href="{{ route('blog.post', $recent->slug) }}">{{ $recent->title }}</a>
                                        </h4>
                                        <span class="text-primary" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">{{ $recent->category }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
