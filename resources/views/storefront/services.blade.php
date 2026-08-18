@extends('layouts.app')

@section('title', 'Motorcycle Repair & Custom Services')
@section('meta_description', 'From engine diagnostics and Dyno performance tuning to custom metal fabrication, check out our full range of professional workshop services.')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 50%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3.5rem; margin-bottom: 10px;">Our Workshop Services</h1>
            <span class="hero-subtitle">High Performance Tuning, Modifications, and Custom Fabrication</span>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="grid grid-3">
                @foreach($services as $service)
                    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                        <div class="card-body">
                            <div class="text-primary" style="font-size: 2.5rem; margin-bottom: 20px;">
                                <i class="fa-solid {{ $service->icon ?? 'fa-wrench' }}"></i>
                            </div>
                            <h3 class="card-title">{{ $service->title }}</h3>
                            <p class="card-desc" style="font-size: 0.95rem; line-height: 1.6; color: var(--text-muted);">
                                {{ $service->description }}
                            </p>
                        </div>
                        <div class="card-body" style="border-top: 1px solid var(--border-light); padding-top: 15px; padding-bottom: 20px;">
                            <div class="flex-between">
                                <div>
                                    <span style="display:block; font-size:0.8rem; color:var(--text-muted); text-transform:uppercase;">Price Estimate</span>
                                    @if($service->price)
                                        <span style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">${{ number_format($service->price, 2) }}</span>
                                    @else
                                        <span style="font-size: 1.2rem; font-weight: 700; color: var(--text-muted);">Custom Quote</span>
                                    @endif
                                </div>
                                <a href="{{ route('contact') }}?service={{ $service->slug }}" class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.9rem;">
                                    Book Service
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="card" style="margin-top: 50px; background-color: rgba(255, 105, 0, 0.03); border-color: var(--primary); padding: 40px; text-align: center;">
                <h3 style="font-size: 2rem; margin-bottom: 10px; text-transform: uppercase;">Need a Custom Build or Full Restoration?</h3>
                <p class="text-muted" style="max-width: 650px; margin: 0 auto 25px;">
                    We specialize in custom frame modifications, vintage carburetor rebuilds, bespoke wiring, and ground-up builds. Send us an enquiry with your design details.
                </p>
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    <i class="fa-solid fa-envelope"></i> Send Design Enquiry
                </a>
            </div>
        </div>
    </section>

@endsection
