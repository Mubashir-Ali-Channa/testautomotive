<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Phoenix V2 Cafe Racer',
                'description' => 'A custom built cafe racer based on a vintage parallel-twin engine. Features hand-formed aluminum tank, custom subframe, clip-on handlebars, leather seat, and full LED modern headlights. Pure retro style combined with modern handling characteristics.',
                'price' => 12499.00,
                'stock' => 2,
                'category' => 'Motorcycles',
                'image_paths' => [
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80'
                ],
                'specifications' => [
                    'Engine' => '450cc Parallel-Twin',
                    'Power' => '48 HP',
                    'Weight' => '165 kg',
                    'Frame' => 'Custom Chromoly Steel',
                    'Brakes' => 'Brembo Dual Disc'
                ],
                'is_featured' => true,
            ],
            [
                'name' => 'Apex Sport Cruiser 1200',
                'description' => 'Experience effortless torque and a low, aggressive stance. Built for long-distance highway cruising with a high-output V-twin engine, comfortable forward controls, and modern digital rider aids (traction control and cornering ABS).',
                'price' => 18999.00,
                'stock' => 1,
                'category' => 'Motorcycles',
                'image_paths' => [
                    'https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1599819811279-d5ad9cccf838?w=800&auto=format&fit=crop&q=80'
                ],
                'specifications' => [
                    'Engine' => '1200cc V-Twin',
                    'Power' => '94 HP',
                    'Torque' => '115 Nm',
                    'Seat Height' => '680 mm',
                    'Fuel Capacity' => '17 L'
                ],
                'is_featured' => true,
            ],
            [
                'name' => 'Terra Venture 850 Adventure',
                'description' => 'Ready for any trail. The Terra Venture is designed to handle highway miles and rocky mountain paths with ease. Spoked tubeless wheels, long-travel adjustable suspension, engine crash bars, and standard aluminum luggage boxes included.',
                'price' => 14500.00,
                'stock' => 3,
                'category' => 'Motorcycles',
                'image_paths' => [
                    'https://images.unsplash.com/photo-1599819811279-d5ad9cccf838?w=800&auto=format&fit=crop&q=80'
                ],
                'specifications' => [
                    'Engine' => '850cc Liquid-Cooled Parallel-Twin',
                    'Power' => '80 HP',
                    'Suspension Travel' => '220 mm',
                    'Front Wheel' => '21 inch Spoked',
                    'Weight' => '205 kg'
                ],
                'is_featured' => false,
            ],
            [
                'name' => 'Predator Full Carbon Exhaust System',
                'description' => 'Premium titanium piping with a high-temp carbon fiber sleeve. Sheds 4.5kg over OEM mufflers and boosts mid-range torque. Includes db-killer insert and carbon mounting bracket.',
                'price' => 849.99,
                'stock' => 8,
                'category' => 'Parts',
                'image_paths' => [
                    'https://images.unsplash.com/photo-1609630875171-b1321377ee65?w=800&auto=format&fit=crop&q=80'
                ],
                'specifications' => [
                    'Material' => 'Titanium & Carbon Fiber',
                    'Weight Savings' => '4.2 kg',
                    'DB Rating' => '96 dB (without db-killer)',
                    'Fitment' => 'Universal 51mm slip-on'
                ],
                'is_featured' => true,
            ],
            [
                'name' => 'Öhlins RXF Fully Adjustable Shock',
                'description' => 'World-class rear mono-shock with hydraulic preload adjustment. Rebound and high/low-speed compression damping adjustments to optimize tire grip and rear-end stability under acceleration.',
                'price' => 1199.00,
                'stock' => 4,
                'category' => 'Parts',
                'image_paths' => [
                    'https://images.unsplash.com/photo-1616422285623-13ff0162193c?w=800&auto=format&fit=crop&q=80'
                ],
                'specifications' => [
                    'Type' => 'Gas-Charged Mono-tube',
                    'Piston Diameter' => '46 mm',
                    'Adjustment' => 'Preload, Compression, Rebound',
                    'Warranty' => '2 Years'
                ],
                'is_featured' => false,
            ],
            [
                'name' => 'Sentinel Carbon Full Face Helmet',
                'description' => 'Aerodynamic carbon fiber shell, premium inner lining, pinlock-ready visor, and emergency quick-release cheek pads. ECE 22.06 and DOT certified for maximum safety and comfort.',
                'price' => 599.99,
                'stock' => 15,
                'category' => 'Gear',
                'image_paths' => [
                    'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=800&auto=format&fit=crop&q=80'
                ],
                'specifications' => [
                    'Shell Material' => '3K Carbon Fiber Grid',
                    'Weight' => '1280g +/- 50g',
                    'Safety Certification' => 'ECE 22.06 & DOT',
                    'Visor Type' => 'Optically correct class 1'
                ],
                'is_featured' => true,
            ],
            [
                'name' => 'Steele Leather Riding Jacket',
                'description' => 'Premium 1.3mm full-grain cowhide leather with CE-Level 2 elbow and shoulder protectors. Removable thermal liner and zipped air vents for multi-season comfort.',
                'price' => 349.99,
                'stock' => 10,
                'category' => 'Gear',
                'image_paths' => [
                    'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&auto=format&fit=crop&q=80'
                ],
                'specifications' => [
                    'Material' => '1.3mm Cowhide Leather',
                    'Armor' => 'CE Level 2 included (Shoulders/Elbows)',
                    'Water Resistance' => 'Shower-resistant'
                ],
                'is_featured' => false,
            ],
            [
                'name' => 'Kevlar Reinforced Racing Gloves',
                'description' => 'Goatskin leather palm with double overlays, hard carbon knuckle guards, and Kevlar lining for maximum abrasion resistance on the street or track.',
                'price' => 89.99,
                'stock' => 20,
                'category' => 'Gear',
                'image_paths' => [
                    'https://images.unsplash.com/photo-1520639888713-7851133b1ed0?w=800&auto=format&fit=crop&q=80'
                ],
                'specifications' => [
                    'Material' => 'Goatskin & Kevlar Thread',
                    'Knuckle Protection' => 'Carbon Fiber Hard Shell',
                    'Touchscreen Compatible' => 'Yes (Index finger)'
                ],
                'is_featured' => false,
            ]
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);
            Product::create($product);
        }
    }
}
