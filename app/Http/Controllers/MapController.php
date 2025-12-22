<?php

namespace App\Http\Controllers;

use App\Models\ObjekPoint;
use App\Models\Kecamatan;
use App\Models\Jalan;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Display the map page
     */
    public function index()
    {
        return view('map.index');
    }
    
    /**
     * Get hotels data for map markers
     * Supports filtering
     */
    public function getHotels(Request $request)
    {
        $query = ObjekPoint::with(['kecamatan', 'images', 'facilities'])
                          ->where('IsActive', true);
        
        // Name search
        if ($request->filled('name')) {
            $query->where('NamaObjek', 'LIKE', "%{$request->name}%");
        }
        
        // Price range
        if ($request->filled('price_min')) {
            $query->where('HargaMin', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('HargaMax', '<=', $request->price_max);
        }
        
        // Star class
        if ($request->filled('stars')) {
            $query->where('StarClass', $request->stars);
        }
        
        // Wilayah filter
        if ($request->filled('wilayah')) {
            $query->whereHas('kecamatan', function($q) use ($request) {
                $q->where('Wilayah', $request->wilayah);
            });
        }
        
        // Kecamatan filter
        if ($request->filled('kecamatan_id')) {
            $query->where('KecamatanID', $request->kecamatan_id);
        }
        
        $hotels = $query->get();
        
        // Transform to GeoJSON format
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $hotels->map(function($hotel) {
                return [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float)$hotel->Longitude, (float)$hotel->Latitude]
                    ],
                    'properties' => [
                        'id' => $hotel->PointID,
                        'name' => $hotel->NamaObjek,
                        'description' => $hotel->Deskripsi,
                        'address' => $hotel->Alamat,
                        'price_min' => $hotel->HargaMin,
                        'price_max' => $hotel->HargaMax,
                        'stars' => $hotel->StarClass,
                        'kecamatan' => $hotel->kecamatan->NamaKecamatan ?? '',
                        'wilayah' => $hotel->kecamatan->Wilayah ?? '',
                        'image' => $hotel->images->first()->ImageURL ?? null,
                        'images' => $hotel->images->pluck('ImageURL')->toArray(),
                    ]
                ];
            })->toArray()
        ];
        
        return response()->json($geojson);
    }
    
    /**
     * Get kecamatan polygons
     */
    public function getKecamatan(Request $request)
    {
        $query = Kecamatan::query();
        
        // Wilayah filter
        if ($request->filled('wilayah')) {
            $query->where('Wilayah', $request->wilayah);
        }
        
        $kecamatans = $query->whereNotNull('PolygonJSON')->get();
        
        // Transform to GeoJSON format
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $kecamatans->map(function($kecamatan) {
                // Ensure PolygonJSON is properly decoded
                $geometry = is_string($kecamatan->PolygonJSON) 
                    ? json_decode($kecamatan->PolygonJSON, true) 
                    : $kecamatan->PolygonJSON;
                
                return [
                    'type' => 'Feature',
                    'geometry' => $geometry,
                    'properties' => [
                        'id' => $kecamatan->KecamatanID,
                        'name' => $kecamatan->NamaKecamatan,
                        'wilayah' => $kecamatan->Wilayah,
                    ]
                ];
            })->toArray()
        ];
        
        return response()->json($geojson);
    }
    
    /**
     * Get routes/roads data
     */
    public function getRoutes(Request $request)
    {
        $routes = Jalan::with(['startPoint', 'endPoint'])->get();
        
        // Transform to GeoJSON format
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $routes->map(function($route) {
                // Ensure KoordinatJSON is properly decoded
                $geometry = is_string($route->KoordinatJSON) 
                    ? json_decode($route->KoordinatJSON, true) 
                    : $route->KoordinatJSON;
                
                return [
                    'type' => 'Feature',
                    'geometry' => $geometry,
                    'properties' => [
                        'id' => $route->JalanID,
                        'name' => $route->NamaJalan,
                        'distance_km' => $route->DistanceKM,
                        'start_hotel' => $route->startPoint->NamaObjek ?? '',
                        'end_hotel' => $route->endPoint->NamaObjek ?? '',
                    ]
                ];
            })->toArray()
        ];
        
        return response()->json($geojson);
    }
    
    /**
     * Find route between two points
     */
    public function findRoute(Request $request)
    {
        $request->validate([
            'start_id' => 'required|integer|exists:objekpoint,PointID',
            'end_id' => 'required|integer|exists:objekpoint,PointID',
        ]);
        
        // Get start and end hotels
        $startHotel = ObjekPoint::find($request->start_id);
        $endHotel = ObjekPoint::find($request->end_id);
        
        if (!$startHotel || !$endHotel) {
            return response()->json([
                'error' => 'Could not find hotels'
            ], 404);
        }
        
        // Find route (bidirectional)
        $route = Jalan::findRoute($startHotel->PointID, $endHotel->PointID);
        
        if (!$route) {
            return response()->json([
                'error' => 'No direct route found between these hotels',
                'start_hotel' => $startHotel->NamaObjek,
                'end_hotel' => $endHotel->NamaObjek,
                'route' => null
            ], 200);
        }
        
        // Ensure geometry is properly decoded
        $geometry = is_string($route->KoordinatJSON) 
            ? json_decode($route->KoordinatJSON, true) 
            : $route->KoordinatJSON;
        
        return response()->json([
            'route' => [
                'type' => 'Feature',
                'geometry' => $geometry,
                'properties' => [
                    'id' => $route->JalanID,
                    'name' => $route->NamaJalan,
                    'distance_km' => $route->DistanceKM,
                    'start_hotel' => $startHotel->NamaObjek,
                    'end_hotel' => $endHotel->NamaObjek,
                ]
            ]
        ]);
    }
    
    /**
     * Radius search - find hotels within radius
     */
    public function radiusSearch(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'numeric|min:0.1|max:50', // km
        ]);
        
        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius ?? 5; // Default 5km
        
        // Haversine formula to calculate distance - use subquery
        $hotels = ObjekPoint::with(['kecamatan', 'images'])
            ->get()
            ->map(function($hotel) use ($lat, $lng) {
                $hotel->distance = $this->calculateHaversineDistance(
                    $lat, $lng, 
                    $hotel->Latitude, $hotel->Longitude
                );
                return $hotel;
            })
            ->filter(function($hotel) use ($radius) {
                return $hotel->distance <= $radius;
            })
            ->sortBy('distance');
        
        // Transform to GeoJSON format
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $hotels->map(function($hotel) {
                return [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float)$hotel->Longitude, (float)$hotel->Latitude]
                    ],
                    'properties' => [
                        'id' => $hotel->PointID,
                        'name' => $hotel->NamaObjek,
                        'description' => $hotel->Deskripsi,
                        'address' => $hotel->Alamat,
                        'price_min' => $hotel->HargaMin,
                        'price_max' => $hotel->HargaMax,
                        'stars' => $hotel->StarClass,
                        'kecamatan' => $hotel->kecamatan->NamaKecamatan ?? '',
                        'wilayah' => $hotel->kecamatan->Wilayah ?? '',
                        'distance' => round($hotel->distance, 2),
                    ]
                ];
            })->toArray()
        ];
        
        return response()->json($geojson);
    }
    
    /**
     * Calculate Haversine distance between two coordinates
     */
    private function calculateHaversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Find nearest hotel to coordinates using Haversine
     */
    private function findNearestHotel($lat, $lng)
    {
        return ObjekPoint::selectRaw("
            *,
            (6371 * acos(
                cos(radians(?)) * 
                cos(radians(Latitude)) * 
                cos(radians(Longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(Latitude))
            )) AS distance
        ", [$lat, $lng, $lat])
        ->orderBy('distance')
        ->first();
    }
}
