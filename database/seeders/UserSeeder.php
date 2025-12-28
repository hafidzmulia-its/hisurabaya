<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin Sistem',         'username' => 'admin',         'email' => 'admin@hisurabaya.test',          'role' => 'admin',        'google_id' => null],
            ['name' => 'Owner Hotel A',        'username' => 'owner_hotela',  'email' => 'owner.hotela@hisurabaya.test',  'role' => 'owner',  'google_id' => null],
            ['name' => 'User Biasa',           'username' => 'user1',         'email' => 'user1@hisurabaya.test',         'role' => 'user', 'google_id' => null],
            ['name' => 'Owner Hotel B',        'username' => 'owner_hotelb',  'email' => 'owner.hotelb@hisurabaya.test',  'role' => 'owner',  'google_id' => null],
            ['name' => 'User Biasa Dua',       'username' => 'user2',         'email' => 'user2@hisurabaya.test',         'role' => 'user', 'google_id' => null],
            ['name' => 'User Biasa Tiga',      'username' => 'user3',         'email' => 'user3@hisurabaya.test',         'role' => 'user', 'google_id' => null],
            ['name' => 'User Biasa Empat',     'username' => 'user4',         'email' => 'user4@hisurabaya.test',         'role' => 'user', 'google_id' => null],
            ['name' => 'User Biasa Lima',      'username' => 'user5',         'email' => 'user5@hisurabaya.test',         'role' => 'user', 'google_id' => null],
            ['name' => 'User Biasa Enam',      'username' => 'user6',         'email' => 'user6@hisurabaya.test',         'role' => 'user', 'google_id' => null],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'username' => $u['username'],
                    'role' => $u['role'],
                    'google_id' => $u['google_id'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
