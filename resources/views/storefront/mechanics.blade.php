@extends('layouts.app')

@section('title', 'Expert Motorcycle Mechanics & Fabricators')
@section('meta_description', 'Meet the certified specialists behind TestAutomotive. Our technicians bring decades of track tuning and custom engineering experience.')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 60%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3.5rem; margin-bottom: 10px;">Our Specialist Mechanics</h1>
            <span class="hero-subtitle">The Passionate Craftspeople Behind Every Custom Build</span>
        </div>
    </section>

    <!-- Mechanics Detailed Grid -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="grid grid-2" style="gap: 40px;">
                @foreach($mechanics as $mechanic)
                    <div class="card" style="display: flex; flex-direction: row; align-items: stretch; gap: 0; overflow: hidden; min-height: 250px;">
                        <div style="width: 40%; min-width: 160px; position: relative;">
                            <img src="{{ $mechanic->avatar_path }}" alt="{{ $mechanic->name }}" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                        </div>
                        <div style="width: 60%; padding: 30px; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <span class="text-primary" style="font-family: var(--font-heading); font-weight: 700; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px;">{{ $mechanic->role }}</span>
                                <h2 style="font-size: 1.8rem; text-transform: uppercase; margin-bottom: 10px;">{{ $mechanic->name }}</h2>
                                <p class="text-muted" style="font-size: 0.9rem; line-height: 1.5; margin-bottom: 15px;">
                                    {{ $mechanic->bio }}
                                </p>
                            </div>
                            <div>
                                <span style="display:block; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Core Specialties</span>
                                <div class="flex" style="flex-wrap: wrap; gap: 5px;">
                                    @if(is_array($mechanic->specialties))
                                        @foreach($mechanic->specialties as $spec)
                                            <span style="font-size: 0.7rem; background-color: var(--bg-input); border: 1px solid var(--border-light); padding: 2px 8px; border-radius: 12px; color: var(--primary); font-weight: 600;">{{ $spec }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
