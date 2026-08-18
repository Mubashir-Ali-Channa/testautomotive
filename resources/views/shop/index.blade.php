@extends('layouts.app')

@section('title', 'Shop Custom Parts, Riding Gear & Motorcycles')
@section('meta_description', 'Browse the TestAutomotive online store for premium replacement parts, high-performance exhaust kits, professional riding gear, and custom motorcycles.')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 40%; margin-bottom: 20px;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3.5rem; margin-bottom: 10px;">Parts & Bike Inventory</h1>
            <span class="hero-subtitle">Premium Custom Motorcycles, Performance Parts, and Professional Riding Gear</span>
        </div>
    </section>

    <!-- Shop Catalog (Livewire Component) -->
    <section class="section section-light scroll-fade" style="padding-top: 40px;">
        <div class="container">
            <livewire:shop-catalog />
        </div>
    </section>

@endsection
