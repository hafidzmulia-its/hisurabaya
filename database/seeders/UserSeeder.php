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
            'name' => 'Admin HISurabaya',
            'username' => 'admin',
            'email' => 'admin@hisurabaya.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        // Owner user 1
        User::create([
            'name' => 'Hotel Owner 1',
            'username' => 'owner1',
            'email' => 'owner1@hisurabaya.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);
        
        // Owner user 2
        User::create([
            'name' => 'Hotel Owner 2',
            'username' => 'owner2',
            'email' => 'owner2@hisurabaya.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);
        
        // Regular user
        User::create([
            'name' => 'Demo User',
            'username' => 'user',
            'email' => 'user@hisurabaya.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
