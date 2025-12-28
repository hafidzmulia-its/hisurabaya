<?php

namespace App\Http\Controllers;

use App\Models\ObjekPoint;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get top 5-star hotels (active only), ordered by star rating
        $topHotels = ObjekPoint::where('IsActive', true)
            ->with(['kecamatan', 'images'])
            ->orderBy('StarClass', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('topHotels'));
    }
}
