<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\JalanController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Protected Map Routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/map', [MapController::class, 'index'])->name('map');
});

// Map API Routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/api/hotels', [MapController::class, 'getHotels'])->name('api.hotels');
    Route::get('/api/kecamatan', [MapController::class, 'getKecamatan'])->name('api.kecamatan');
    Route::get('/api/routes', [MapController::class, 'getRoutes'])->name('api.routes');
    Route::post('/api/find-route', [MapController::class, 'findRoute'])->name('api.find-route');
    Route::get('/api/radius-search', [MapController::class, 'radiusSearch'])->name('api.radius-search');
});

// Hotel Management Routes (admin & owner only)
Route::middleware(['auth'])->group(function () {
    Route::resource('hotels', HotelController::class);
});

// Admin Only Routes (Jalan & Kecamatan Management)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('jalan', JalanController::class);
    Route::resource('kecamatan', KecamatanController::class);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
