<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Full Engine Tuning',
                'description' => 'Comprehensive performance diagnostics, valve adjustments, spark plug replacement, fuel injection calibration, and carbon cleaning for peak power.',
                'price' => 249.99,
                'icon' => 'fa-cog',
            ],
            [
                'title' => 'Brake Calibration & Service',
                'description' => 'Precision pad replacement, rotor resurfacing, brake fluid flush, and caliper service to ensure maximum stopping power when you need it.',
                'price' => 119.99,
                'icon' => 'fa-shield-alt',
            ],
            [
                'title' => 'Custom Exhaust Installation',
                'description' => 'Upgrade your sound and horsepower with slip-on or full exhaust system installs. Includes fuel map flashing for optimal performance.',
                'price' => 179.99,
                'icon' => 'fa-wind',
            ],
            [
                'title' => 'Suspension Tuning',
                'description' => 'Sag setup, compression and rebound adjustments tailored to your weight and riding style (street, track, or off-road adventure).',
                'price' => 149.99,
                'icon' => 'fa-level-up-alt',
            ],
            [
                'title' => 'Custom Painting & Detailing',
                'description' => 'From minor scratch repair and premium detail polishing to full custom tank painting, pinstriping, and ceramic coating coatings.',
                'price' => 399.99,
                'icon' => 'fa-paint-brush',
            ],
            [
                'title' => 'Full Safety Diagnostics',
                'description' => 'A complete 50-point inspection covering electrics, chassis alignment, chain tension, tyre wear, and safety fluid checks.',
                'price' => 79.99,
                'icon' => 'fa-check-circle',
            ],
        ];

        foreach ($services as $service) {
            $service['slug'] = Str::slug($service['title']);
            Service::create($service);
        }
    }
}
