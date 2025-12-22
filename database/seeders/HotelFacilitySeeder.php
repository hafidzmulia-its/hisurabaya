<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelFacilitySeeder extends Seeder
{
    public function run(): void
    {
        // Assign facilities to hotels
        // Hotel Majapahit (PointID: 1) - Luxury hotel
        $this->assignFacilities(1, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]);
        
        // Shangri-La (PointID: 2) - Luxury hotel
        $this->assignFacilities(2, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 16, 17, 18, 20, 22, 23, 24]);
        
        // JW Marriott (PointID: 3) - Luxury hotel
        $this->assignFacilities(3, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 13, 14, 15, 16, 17, 18, 20, 21, 22, 23, 24]);
        
        // Vasa Hotel (PointID: 4) - Mid-range
        $this->assignFacilities(4, [1, 2, 3, 4, 6, 9, 10, 14, 15, 17, 18, 24]);
        
        // Grand Mercure (PointID: 5) - Mid-range
        $this->assignFacilities(5, [1, 2, 3, 4, 5, 6, 9, 10, 14, 15, 16, 17, 18, 20, 24]);
        
        // Swiss-Belinn (PointID: 6) - Budget
        $this->assignFacilities(6, [1, 2, 3, 4, 6, 14, 17, 18, 24]);
        
        // Ciputra World (PointID: 7) - Luxury
        $this->assignFacilities(7, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 20, 21, 22, 23, 24]);
        
        // Favehotel (PointID: 8) - Budget
        $this->assignFacilities(8, [1, 2, 3, 4, 14, 18, 24]);
        
        // Pop! Hotel (PointID: 9) - Budget
        $this->assignFacilities(9, [1, 3, 4, 14, 24]);
        
        // The Westin (PointID: 10) - Luxury
        $this->assignFacilities(10, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 22, 23, 24]);
    }
    
    private function assignFacilities(int $pointId, array $facilityIds): void
    {
        foreach ($facilityIds as $facilityId) {
            DB::table('hotel_facilities')->insert([
                'PointID' => $pointId,
                'FacilityID' => $facilityId,
                'IsAvailable' => true,
                'ExtraPrice' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
