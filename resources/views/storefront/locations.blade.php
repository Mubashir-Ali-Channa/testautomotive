@extends('layouts.app')

@section('title', 'Our Locations')

@section('content')

    <!-- Inner Hero -->
    <section class="hero-section" style="padding: 60px 0; background-position: center 70%;">
        <div class="container text-center">
            <h1 class="hero-title" style="font-size: 3.5rem; margin-bottom: 10px;">Our Workshop Locations</h1>
            <span class="hero-subtitle">Visit Us for Diagnostics, Parts Pickups, or a Cup of Coffee</span>
        </div>
    </section>

    <!-- Map & Details Grid -->
    <section class="section section-light scroll-fade">
        <div class="container">
            <div class="grid grid-2" style="gap: 50px;">
                <div>
                    <h2 style="font-size: 2.2rem; text-transform: uppercase; margin-bottom: 25px;">Main Headquarters & Tuning Center</h2>
                    
                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--primary); text-transform: uppercase; font-size: 1rem; margin-bottom: 5px;"><i class="fa-solid fa-location-dot"></i> Address</h4>
                        <p style="font-size: 1.1rem; color: var(--text-dark);">
                            {{ App\Models\Setting::get('address', '789 Throttle Lane, Exhaust City, EC 90210') }}
                        </p>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--primary); text-transform: uppercase; font-size: 1rem; margin-bottom: 5px;"><i class="fa-solid fa-clock"></i> Opening Hours</h4>
                        <p style="font-size: 1.1rem; color: var(--text-dark);">
                            {{ App\Models\Setting::get('opening_hours', 'Mon - Fri: 8:00 AM - 6:00 PM, Sat: 9:00 AM - 4:00 PM') }}
                        </p>
                        <p class="text-muted" style="font-size: 0.9rem; margin-top: 5px;">Workshop closed on Sundays for track days.</p>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h4 style="color: var(--primary); text-transform: uppercase; font-size: 1rem; margin-bottom: 5px;"><i class="fa-solid fa-phone"></i> Phone & Email</h4>
                        <p style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 3px;">
                            Phone: {{ App\Models\Setting::get('contact_phone', '+1 (555) 123-4567') }}
                        </p>
                        <p style="font-size: 1.1rem; color: var(--text-dark);">
                            Email: {{ App\Models\Setting::get('contact_email', 'info@testautomotive.com') }}
                        </p>
                    </div>

                    <div class="flex" style="gap: 15px; margin-top: 40px;">
                        <a href="{{ route('contact') }}" class="btn btn-primary">Get in Touch</a>
                        <a href="{{ route('services') }}" class="btn btn-secondary">Explore Services</a>
                    </div>
                </div>

                <div style="height: 450px; border: 1px solid var(--border-light); border-radius: 8px; box-shadow: var(--shadow); position: relative; overflow: hidden;">
                    <div id="map" style="width: 100%; height: 100%; z-index: 5;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leaflet Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @php
                $coords = App\Models\Setting::get('map_coordinates', '34.0522,-118.2437');
                $parts = explode(',', $coords);
                $lat = isset($parts[0]) ? trim($parts[0]) : '34.0522';
                $lng = isset($parts[1]) ? trim($parts[1]) : '-118.2437';
            @endphp
            const lat = {{ $lat }};
            const lng = {{ $lng }};
            const map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>TestAutomotive Garage</b><br>{{ App\Models\Setting::get('address', '789 Throttle Lane, Exhaust City, EC 90210') }}')
                .openPopup();
        });
    </script>

@endsection
