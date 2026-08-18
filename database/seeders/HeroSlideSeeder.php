<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        HeroSlide::create([
            'title' => 'WE DELIVER PERFORMANCE YOU CAN FEEL ON EVERY RIDE',
            'subtitle' => 'YOUR PREMIER MOTORCYCLE SERVICE CENTER',
            'image_path' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=1600&auto=format&fit=crop&q=80',
            'button_text' => 'GET IN TOUCH',
            'button_link' => '#contact',
            'order' => 1,
        ]);

        HeroSlide::create([
            'title' => 'CRAFTING UNIQUE CUSTOM CAFE RACERS & BOBBERS',
            'subtitle' => 'HAND-MADE FABRICATION & PRECISION ENGINE TUNING',
            'image_path' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=1600&auto=format&fit=crop&q=80',
            'button_text' => 'OUR SERVICES',
            'button_link' => '/services',
            'order' => 2,
        ]);

        HeroSlide::create([
            'title' => 'EXPERIENCE THE ADVENTURE OF ROAD TRAVEL',
            'subtitle' => 'READY FOR ANY PATH WITH ADVENTURE GEAR & TIRES',
            'image_path' => 'https://images.unsplash.com/photo-1599819811279-d5ad9cccf838?w=1600&auto=format&fit=crop&q=80',
            'button_text' => 'SHOP CATALOG',
            'button_link' => '/shop',
            'order' => 3,
        ]);
    }
}
