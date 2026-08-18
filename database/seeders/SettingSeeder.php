<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'TestAutomotive Specialist',
            'contact_email' => 'info@testautomotive.com',
            'contact_phone' => '+1 (555) 123-4567',
            'address' => '789 Throttle Lane, Exhaust City, EC 90210',
            'opening_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM, Sat: 9:00 AM - 4:00 PM',
            'facebook_url' => 'https://facebook.com/testautomotive',
            'instagram_url' => 'https://instagram.com/testautomotive',
            'twitter_url' => 'https://twitter.com/testautomotive',
            'map_coordinates' => '34.0522,-118.2437', // Los Angeles coordinates for map placeholder
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
