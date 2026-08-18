<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => '10 Essential Safety Checks Before Every Motorcycle Ride',
                'content' => 'Before you throw your leg over your bike, it’s vital to perform a quick walk-around safety check. Taking just five minutes can save you from a breakdown or, worse, a serious accident. In this guide, we break down the standard T-CLOCS checklist: Tyres, Controls, Lights, Oils, Chassis, and Stands. Check tyre pressure and look for dry rot or punctures. Ensure your throttle snaps back cleanly and clutch cables aren’t frayed. Test front and rear brakes, high/low beam headlights, and brake lights. Verify engine oil, coolant, and brake fluid levels. Inspect the drive chain for proper tension and lubrication. These simple checks build a foundation of safety that keeps you riding year after year.',
                'image_path' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&auto=format&fit=crop&q=80',
                'category' => 'Safety & Maintenance',
            ],
            [
                'title' => 'How to Maintain Your Motorcycle Chain Like a Pro',
                'content' => 'Your motorcycle’s chain is the vital link transferring power from the engine to the rear tyre, and it operates in a harsh, open environment. Neglecting chain maintenance leads to premature wear of both the chain and sprockets, reducing fuel efficiency and creating a safety hazard. We recommend cleaning and lubricating your chain every 300 to 500 miles. Start by placing the bike on a paddock stand. Use a dedicated chain cleaner or kerosene and a soft grunge brush to remove built-up road grit. Wipe dry, then apply a high-quality chain wax or lube evenly to the inside run of the chain while rotating the wheel. Let it set for 15 minutes before riding to avoid fling-off. Proper tension is just as important—aim for 25-35mm of slack at the tightest point.',
                'image_path' => 'https://images.unsplash.com/photo-1609630875171-b1321377ee65?w=800&auto=format&fit=crop&q=80',
                'category' => 'Maintenance Guides',
            ],
            [
                'title' => 'The Rise of Custom Cafe Racers: History and Aesthetic',
                'content' => 'Originating in 1960s London, cafe racers were built by young motorcyclists who stripped down standard bikes to ride between transport cafes. The goal was simple: make the bike as light, fast, and agile as possible to hit the magic "ton" (100 mph). Today, the cafe racer movement has evolved into a global custom subculture. Characterized by low-mounted clip-on handlebars, rear-set footpegs, a distinctive single-seat cowl, and a straight bone-line from the headlight to the rear tail, these bikes are the epitome of minimalism. In this article, we explore how modern engine building and carbon fiber fabrication are being merged with vintage British and Japanese twins to create custom functional art.',
                'image_path' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
                'category' => 'Custom Culture',
            ]
        ];

        foreach ($posts as $post) {
            $post['slug'] = Str::slug($post['title']);
            BlogPost::create($post);
        }
    }
}
