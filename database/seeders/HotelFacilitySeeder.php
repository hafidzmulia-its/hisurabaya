<?php

namespace Database\Seeders;

use App\Models\FacilityType;
use App\Models\ObjekPoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilityMap = FacilityType::all()->mapWithKeys(function ($f) {
            return [$f->Name => $f->getKey()];
        })->toArray();

        $pointMap = ObjekPoint::all()->mapWithKeys(function ($p) {
            return [$p->NamaObjek => $p->getKey()];
        })->toArray();

        $rows = [
            ['NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)', 'FacilityName' => 'Gym',              'IsAvailable' => 0, 'ExtraPrice' => 0],
            ['NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'HOTEL BUMI SURABAYA', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BUMI SURABAYA', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BUMI SURABAYA', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 200000],
            ['NamaObjek' => 'HOTEL BUMI SURABAYA', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BUMI SURABAYA', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BUMI SURABAYA', 'FacilityName' => 'Gym',              'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BUMI SURABAYA', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'FAVE HOTEL RUNGKUT', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FAVE HOTEL RUNGKUT', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FAVE HOTEL RUNGKUT', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FAVE HOTEL RUNGKUT', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FAVE HOTEL RUNGKUT', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FAVE HOTEL RUNGKUT', 'FacilityName' => 'Gym',              'IsAvailable' => 0, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FAVE HOTEL RUNGKUT', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR', 'FacilityName' => 'Gym',              'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 250000],
            ['NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA', 'FacilityName' => 'Gym',              'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'HOTEL SHANGRILA', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL SHANGRILA', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL SHANGRILA', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 300000],
            ['NamaObjek' => 'HOTEL SHANGRILA', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL SHANGRILA', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL SHANGRILA', 'FacilityName' => 'Gym',              'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL SHANGRILA', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'THE CAPITAL HOTEL SURABAYA', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE CAPITAL HOTEL SURABAYA', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE CAPITAL HOTEL SURABAYA', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE CAPITAL HOTEL SURABAYA', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE CAPITAL HOTEL SURABAYA', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 0, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE CAPITAL HOTEL SURABAYA', 'FacilityName' => 'Gym',              'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE CAPITAL HOTEL SURABAYA', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'THE ALANA SURABAYA', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE ALANA SURABAYA', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE ALANA SURABAYA', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE ALANA SURABAYA', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE ALANA SURABAYA', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE ALANA SURABAYA', 'FacilityName' => 'Gym',              'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'THE ALANA SURABAYA', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 42000],
            ['NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 0, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL', 'FacilityName' => 'Gym',              'IsAvailable' => 0, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],

            ['NamaObjek' => 'HOTEL NEW GRAND PARK', 'FacilityName' => 'WiFi',             'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL NEW GRAND PARK', 'FacilityName' => 'Parkir',           'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL NEW GRAND PARK', 'FacilityName' => 'Sarapan',          'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL NEW GRAND PARK', 'FacilityName' => 'AC',               'IsAvailable' => 1, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL NEW GRAND PARK', 'FacilityName' => 'Kolam Renang',     'IsAvailable' => 0, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL NEW GRAND PARK', 'FacilityName' => 'Gym',              'IsAvailable' => 0, 'ExtraPrice' => 0],
            ['NamaObjek' => 'HOTEL NEW GRAND PARK', 'FacilityName' => 'Resepsionis 24 Jam','IsAvailable'=> 1, 'ExtraPrice' => 0],
        ];

        foreach ($rows as $r) {
            if (!isset($pointMap[$r['NamaObjek']])) {
                throw new \RuntimeException("ObjekPoint not found for facility: {$r['NamaObjek']}");
            }
            if (!isset($facilityMap[$r['FacilityName']])) {
                throw new \RuntimeException("FacilityType not found: {$r['FacilityName']}");
            }

            $pointId = $pointMap[$r['NamaObjek']];
            $facilityId = $facilityMap[$r['FacilityName']];

            DB::table('hotel_facilities')->updateOrInsert(
                ['PointID' => $pointId, 'FacilityID' => $facilityId],
                [
                    'IsAvailable' => (bool)$r['IsAvailable'],
                    'ExtraPrice' => (int)$r['ExtraPrice'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
