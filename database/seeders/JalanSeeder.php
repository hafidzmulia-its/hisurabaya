<?php

namespace Database\Seeders;

use App\Models\Jalan;
use Illuminate\Database\Seeder;

class JalanSeeder extends Seeder
{
    public function run(): void
    {
        // Sample routes between hotels
        $routes = [
            [
                'NamaJalan' => 'Route: Majapahit - JW Marriott',
                'KoordinatJSON' => json_encode([
                    'type' => 'LineString',
                    'coordinates' => [
                        [112.7379, -7.2632], // Majapahit
                        [112.7419, -7.2649], // JW Marriott
                    ]
                ]),
                'StartPointID' => 1,
                'EndPointID' => 3,
                'DistanceKM' => 0.45,
            ],
            [
                'NamaJalan' => 'Route: Majapahit - Swiss-Belinn',
                'KoordinatJSON' => json_encode([
                    'type' => 'LineString',
                    'coordinates' => [
                        [112.7379, -7.2632], // Majapahit
                        [112.7398, -7.2658], // Swiss-Belinn
                    ]
                ]),
                'StartPointID' => 1,
                'EndPointID' => 6,
                'DistanceKM' => 0.35,
            ],
            [
                'NamaJalan' => 'Route: Shangri-La - Ciputra World',
                'KoordinatJSON' => json_encode([
                    'type' => 'LineString',
                    'coordinates' => [
                        [112.7341, -7.2886], // Shangri-La
                        [112.7285, -7.2843], // Ciputra World
                    ]
                ]),
                'StartPointID' => 2,
                'EndPointID' => 7,
                'DistanceKM' => 0.65,
            ],
            [
                'NamaJalan' => 'Route: Ciputra World - The Westin',
                'KoordinatJSON' => json_encode([
                    'type' => 'LineString',
                    'coordinates' => [
                        [112.7285, -7.2843], // Ciputra World
                        [112.7291, -7.2821], // The Westin
                    ]
                ]),
                'StartPointID' => 7,
                'EndPointID' => 10,
                'DistanceKM' => 0.25,
            ],
            [
                'NamaJalan' => 'Route: Gubeng - Vasa Hotel',
                'KoordinatJSON' => json_encode([
                    'type' => 'LineString',
                    'coordinates' => [
                        [112.7509, -7.2713], // Pop! Hotel Gubeng
                        [112.7649, -7.2839], // Vasa Hotel
                    ]
                ]),
                'StartPointID' => 9,
                'EndPointID' => 4,
                'DistanceKM' => 1.8,
            ],
            [
                'NamaJalan' => 'Route: Grand Mercure - Favehotel',
                'KoordinatJSON' => json_encode([
                    'type' => 'LineString',
                    'coordinates' => [
                        [112.7378, -7.2522], // Grand Mercure
                        [112.7451, -7.2617], // Favehotel
                    ]
                ]),
                'StartPointID' => 5,
                'EndPointID' => 8,
                'DistanceKM' => 1.2,
            ],
        ];
        
        foreach ($routes as $route) {
            Jalan::create($route);
        }
    }
}
