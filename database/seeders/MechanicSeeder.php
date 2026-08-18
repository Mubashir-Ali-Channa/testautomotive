<?php

namespace Database\Seeders;

use App\Models\Mechanic;
use Illuminate\Database\Seeder;

class MechanicSeeder extends Seeder
{
    public function run(): void
    {
        $mechanics = [
            [
                'name' => 'Ryder Vance',
                'role' => 'Founder & Master Builder',
                'bio' => 'With over 18 years of experience building and restoring classic motorcycles, Ryder is the visionary behind TestAutomotive. His custom builds have been featured in international magazines.',
                'avatar_path' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
                'specialties' => ['Chassis Fabrication', 'Vintage Restoration', 'Engine Rebuilds'],
            ],
            [
                'name' => 'Axel Cross',
                'role' => 'Engine Specialist',
                'bio' => 'Axel lives for speed and performance. He specializes in high-horsepower tuning, fuel injection maps, and racing engine prep. If you want your bike to scream, Axel is your man.',
                'avatar_path' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&auto=format&fit=crop&q=80',
                'specialties' => ['ECU Flashing', 'Dyno Tuning', 'Performance Headers'],
            ],
            [
                'name' => 'Jace Thorne',
                'role' => 'Electrical & Diagnostic Lead',
                'bio' => 'Electrical gremlins are Jace’s specialty. He has advanced certifications in modern motorcycle computers, wiring harnesses, and custom lighting setups.',
                'avatar_path' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=500&auto=format&fit=crop&q=80',
                'specialties' => ['Electrical Systems', 'CAN bus Diagnostics', 'Custom LED Harnesses'],
            ],
            [
                'name' => 'Lara Steele',
                'role' => 'Custom Paint Expert',
                'bio' => 'Lara is an artist whose canvas is the motorcycle tank. From hand-laid pinstripes and metal flake paints to custom airbrush work and flawless clear coats, she makes bikes look as fast as they go.',
                'avatar_path' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=500&auto=format&fit=crop&q=80',
                'specialties' => ['Custom Airbrush', 'Pinstriping', 'Ceramic Coatings'],
            ],
        ];

        foreach ($mechanics as $mechanic) {
            Mechanic::create($mechanic);
        }
    }
}
