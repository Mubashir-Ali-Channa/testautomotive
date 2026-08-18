@extends('layouts.app')

@section('title', 'Motorcycle Culture & DIY Guides Blog')
@section('meta_description', 'Read the latest motorcycle maintenance guides, custom build diaries, safety checklists, and culture articles from our garage.')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 20%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3.5rem; margin-bottom: 10px;">Our Garage Blog</h1>
            <span class="hero-subtitle">Maintenance Guides, Safety Checklists, and Motorcycle Culture</span>
        </div>
    </section>

    <!-- Blogs List -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="grid grid-3">
                @foreach($posts as $post)
                    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div class="card-img-wrapper" style="height: 190px;">
                                <a href="{{ route('blog.post', $post->slug) }}">
                                    <img src="{{ $post->image_path }}" alt="{{ $post->title }}">
                                </a>
                            </div>
                            <div class="card-body">
                                <span class="text-primary" style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin-bottom: 10px; display: block;">{{ $post->category }}</span>
                                <h3 class="card-title" style="font-size: 1.35rem; line-height: 1.3; margin-bottom: 15px;">
                                    <a href="{{ route('blog.post', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="card-desc" style="font-size: 0.9rem; line-height: 1.6; color: var(--text-muted); margin-bottom: 15px;">
                                    {{ Str::limit($post->content, 130) }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top: 0; padding-bottom: 25px;">
                            <a href="{{ route('blog.post', $post->slug) }}" style="color: var(--primary); font-weight: bold; font-size: 0.9rem; text-transform: uppercase; display: inline-flex; align-items: center; gap: 5px;">
                                Read Full Guide <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div style="margin-top: 50px; display: flex; justify-content: center; gap: 10px;">
                {{ $posts->links() }}
            </div>
        </div>
    </section>

@endsection
