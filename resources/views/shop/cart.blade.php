@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 50px 0; background-position: center 30%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3rem; margin-bottom: 5px;">Your Shopping Cart</h1>
            <span class="hero-subtitle">Review items before proceeding to checkout</span>
        </div>
    </section>

    <!-- Cart Manager (Livewire Component) -->
    <section class="section section-light" style="padding-top: 50px;">
        <div class="container">
            <livewire:cart-manager />
        </div>
    </section>

@endsection
