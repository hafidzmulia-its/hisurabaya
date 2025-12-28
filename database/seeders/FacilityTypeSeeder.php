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
            'Sarapan',
            'AC',
            'Kolam Renang',
            'Gym',
            'Resepsionis 24 Jam',
        ];

        foreach ($facilities as $name) {
            FacilityType::updateOrCreate(['Name' => $name], ['Name' => $name]);
        }
    }
}
