<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        Testimonial::create([
            'name' => 'Marcus Kelly',
            'role' => 'Panigale V4 Rider',
            'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80',
            'content' => 'The engine tuning Axel did on my Ducati is unbelievable. Better throttle response, more mid-range torque, and it sounds absolutely beastly now!',
            'rating' => 5,
            'is_active' => true,
        ]);

        Testimonial::create([
            'name' => 'Sarah Jenkins',
            'role' => 'Vintage Bike Enthusiast',
            'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80',
            'content' => 'Ryder Vance designed a custom subframe and aluminum seat cowl for my CB550 restore. The fabrication work is literal custom art. The line is absolute perfection.',
            'rating' => 5,
            'is_active' => true,
        ]);

        Testimonial::create([
            'name' => 'Ethan Ross',
            'role' => 'Cruiser Commuter',
            'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&auto=format&fit=crop&q=80',
            'content' => 'I bought the Kevlar gloves and Steele jacket online. Shipping was quick, fits exactly as sized, and the leather quality is exceptional. Best shop around!',
            'rating' => 5,
            'is_active' => true,
        ]);
    }
}
