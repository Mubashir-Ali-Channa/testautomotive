<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        Career::create([
            'title' => 'Lead Motorcycle Mechanic',
            'department' => 'Workshop Service',
            'description' => 'We are seeking an experienced Lead Mechanic to head our service department. You will diagnose and perform repairs, lead suspension setups, tune performance modifications, and oversee junior mechanics.',
            'requirements' => "Minimum 5 years professional experience with major brands (Honda, Yamaha, BMW, Ducati).\nAdvanced engine diagnostics and electrical mapping skills.\nDetail-oriented approach to safety and customer service.\nMotorcycle license required.",
            'type' => 'Full-time',
        ]);

        Career::create([
            'title' => 'Custom Paint & Fabrication Apprentice',
            'department' => 'Custom Builds & Restoration',
            'description' => 'Work directly under our master builders to learn frame fabrication, aluminum shaping, tank painting, prep work, and final detailing.',
            'requirements' => "Basic understanding of workshop safety and metal prep.\nStrong willingness to learn and passion for custom motorcycle builds.\nArtistic eye for detail and design.\nPortfolio of past paint or metalwork projects is a plus.",
            'type' => 'Full-time',
        ]);
    }
}
