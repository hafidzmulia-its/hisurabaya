<?php

namespace Database\Seeders;

use App\Models\FacilityType;
use Illuminate\Database\Seeder;

class FacilityTypeSeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            'WiFi',
            'Parkir',
            'AC',
            'TV',
            'Kolam Renang',
            'Restoran',
            'Gym',
            'Spa',
            'Laundry',
            'Room Service',
            'Mini Bar',
            'Coffee Maker',
            'Bathtub',
            'Shower',
            'Hair Dryer',
            'Safe Box',
            'Telepon',
            'Breakfast',
            'Airport Shuttle',
            'Meeting Room',
            'Ballroom',
            'Business Center',
            'Concierge',
            '24-Hour Front Desk',
        ];
        
        foreach ($facilities as $facility) {
            FacilityType::create(['Name' => $facility]);
        }
    }
}
