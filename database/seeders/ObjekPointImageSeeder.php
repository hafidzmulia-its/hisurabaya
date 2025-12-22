<?php

namespace Database\Seeders;

use App\Models\ObjekPointImage;
use Illuminate\Database\Seeder;

class ObjekPointImageSeeder extends Seeder
{
    public function run(): void
    {
        // Sample images for hotels (using placeholder URLs)
        $images = [
            // Hotel Majapahit (PointID: 1)
            ['PointID' => 1, 'ImageURL' => 'https://placehold.co/800x600/4F46E5/white?text=Majapahit+Exterior'],
            ['PointID' => 1, 'ImageURL' => 'https://placehold.co/800x600/7C3AED/white?text=Majapahit+Room'],
            ['PointID' => 1, 'ImageURL' => 'https://placehold.co/800x600/2563EB/white?text=Majapahit+Pool'],
            
            // Shangri-La (PointID: 2)
            ['PointID' => 2, 'ImageURL' => 'https://placehold.co/800x600/DC2626/white?text=Shangri-La+View'],
            ['PointID' => 2, 'ImageURL' => 'https://placehold.co/800x600/EA580C/white?text=Shangri-La+Suite'],
            
            // JW Marriott (PointID: 3)
            ['PointID' => 3, 'ImageURL' => 'https://placehold.co/800x600/16A34A/white?text=Marriott+Lobby'],
            ['PointID' => 3, 'ImageURL' => 'https://placehold.co/800x600/059669/white?text=Marriott+Room'],
            
            // Vasa Hotel (PointID: 4)
            ['PointID' => 4, 'ImageURL' => 'https://placehold.co/800x600/0891B2/white?text=Vasa+Hotel'],
            
            // Grand Mercure (PointID: 5)
            ['PointID' => 5, 'ImageURL' => 'https://placehold.co/800x600/7C2D12/white?text=Grand+Mercure'],
            
            // Swiss-Belinn (PointID: 6)
            ['PointID' => 6, 'ImageURL' => 'https://placehold.co/800x600/BE123C/white?text=Swiss-Belinn'],
            
            // Ciputra World (PointID: 7)
            ['PointID' => 7, 'ImageURL' => 'https://placehold.co/800x600/9333EA/white?text=Ciputra+World'],
            ['PointID' => 7, 'ImageURL' => 'https://placehold.co/800x600/7C3AED/white?text=Ciputra+Room'],
            
            // Favehotel (PointID: 8)
            ['PointID' => 8, 'ImageURL' => 'https://placehold.co/800x600/0369A1/white?text=Favehotel'],
            
            // Pop! Hotel (PointID: 9)
            ['PointID' => 9, 'ImageURL' => 'https://placehold.co/800x600/CA8A04/white?text=Pop+Hotel'],
            
            // The Westin (PointID: 10)
            ['PointID' => 10, 'ImageURL' => 'https://placehold.co/800x600/4338CA/white?text=Westin+Exterior'],
            ['PointID' => 10, 'ImageURL' => 'https://placehold.co/800x600/6366F1/white?text=Westin+Spa'],
        ];
        
        foreach ($images as $image) {
            ObjekPointImage::create($image);
        }
    }
}
