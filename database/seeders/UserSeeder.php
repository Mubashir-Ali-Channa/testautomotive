<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@testautomotive.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Regular customer user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@testautomotive.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
