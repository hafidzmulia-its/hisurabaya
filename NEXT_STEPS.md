# NEXT STEPS - Development Roadmap

## ✅ What's Done (Foundation Complete)

### Backend Core
- ✅ Laravel 12 installed with Breeze (Alpine + Tailwind)
- ✅ Socialite installed (ready for Google OAuth)
- ✅ Database migrations (7 tables)
- ✅ Eloquent models with relationships (5 models)
- ✅ Database seeders with sample data
- ✅ .env configured for SQL Server

### Project Files Created
- ✅ README.md - Full project documentation
- ✅ SETUP_GUIDE.md - Installation instructions
- ✅ CDM_DIAGRAM.md - Database schema reference
- ✅ MVP.txt - Original requirements

---

## 🎯 What to Build Next

### Phase 1: Map Integration (Priority: HIGH)
**Goal:** Display hotels on OpenStreetMap

#### 1.1 Install Leaflet.js
```powershell
npm install leaflet
```

#### 1.2 Create MapController
```powershell
php artisan make:controller MapController
```

**Methods needed:**
- `index()` - Display map page
- `getHotels()` - API endpoint: return hotels as GeoJSON
- `getKecamatan()` - API endpoint: return polygons
- `getRoutes()` - API endpoint: return routes

#### 1.3 Create Map View
File: `resources/views/map/index.blade.php`

**Structure:**
```html
<div id="map" class="h-screen w-full"></div>

<script>
// Initialize Leaflet map
const map = L.map('map').setView([-7.2575, 112.7521], 12);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// Fetch and display hotels
fetch('/api/hotels')
  .then(response => response.json())
  .then(data => {
    // Add markers
  });
</script>
```

#### 1.4 Add Routes
File: `routes/web.php`
```php
use App\Http\Controllers\MapController;

Route::get('/map', [MapController::class, 'index'])->name('map');
Route::get('/api/hotels', [MapController::class, 'getHotels']);
Route::get('/api/kecamatan', [MapController::class, 'getKecamatan']);
Route::get('/api/routes', [MapController::class, 'getRoutes']);
```

---

### Phase 2: Hotel CRUD (Priority: HIGH)
**Goal:** Admin/Owner can manage hotels

#### 2.1 Create HotelController
```powershell
php artisan make:controller Admin/HotelController --resource
```

#### 2.2 Create Views
- `resources/views/admin/hotels/index.blade.php` - List hotels
- `resources/views/admin/hotels/create.blade.php` - Create form
- `resources/views/admin/hotels/edit.blade.php` - Edit form
- `resources/views/admin/hotels/show.blade.php` - Detail view

#### 2.3 Add Middleware for Authorization
```php
Route::middleware(['auth'])->group(function () {
    Route::resource('admin/hotels', HotelController::class);
});
```

#### 2.4 Implement Methods
- `index()` - List hotels (owner sees only their hotels)
- `create()` - Show create form
- `store()` - Save new hotel
- `edit()` - Show edit form
- `update()` - Update hotel
- `destroy()` - Delete hotel

---

### Phase 3: Search & Filter (Priority: HIGH)
**Goal:** Users can search hotels

#### 3.1 Update MapController
Add filter methods:
```php
public function getHotels(Request $request)
{
    $query = ObjekPoint::with(['kecamatan', 'images'])->active();
    
    // Name search
    if ($request->name) {
        $query->where('NamaObjek', 'LIKE', "%{$request->name}%");
    }
    
    // Price range
    if ($request->price_min) {
        $query->where('HargaMin', '>=', $request->price_min);
    }
    if ($request->price_max) {
        $query->where('HargaMax', '<=', $request->price_max);
    }
    
    // Star class
    if ($request->stars) {
        $query->where('StarClass', $request->stars);
    }
    
    // Wilayah
    if ($request->wilayah) {
        $query->wilayah($request->wilayah);
    }
    
    // Kecamatan
    if ($request->kecamatan_id) {
        $query->where('KecamatanID', $request->kecamatan_id);
    }
    
    return response()->json($query->get());
}
```

#### 3.2 Create Filter UI (Alpine.js)
```html
<div x-data="{ 
    filters: {
        name: '',
        price_min: null,
        price_max: null,
        stars: null,
        wilayah: '',
        kecamatan_id: null
    }
}">
    <!-- Filter inputs -->
    <input x-model="filters.name" placeholder="Search hotels...">
    
    <!-- Apply filters button -->
    <button @click="applyFilters()">Search</button>
</div>
```

---

### Phase 4: Google OAuth (Priority: MEDIUM)
**Goal:** Users can login with Google

#### 4.1 Create GoogleController
```powershell
php artisan make:controller Auth/GoogleController
```

#### 4.2 Implement OAuth Flow
```php
public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->user();
    
    $user = User::updateOrCreate(
        ['google_id' => $googleUser->id],
        [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'email_verified_at' => now(),
        ]
    );
    
    Auth::login($user);
    
    return redirect('/dashboard');
}
```

#### 4.3 Add Routes
```php
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
```

#### 4.4 Update Login View
Add "Login with Google" button

---

### Phase 5: Route Mode (Priority: MEDIUM)
**Goal:** Show routes between hotels

#### 5.1 Create Route Helper
File: `app/Helpers/RouteHelper.php`
```php
class RouteHelper
{
    public static function findNearestHotel($lat, $lng)
    {
        // Haversine formula to find nearest hotel
        return ObjekPoint::selectRaw("
            PointID,
            NamaObjek,
            Latitude,
            Longitude,
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
```

#### 5.2 Add Route Endpoint
```php
public function findRoute(Request $request)
{
    $start = RouteHelper::findNearestHotel(
        $request->start_lat, 
        $request->start_lng
    );
    
    $end = RouteHelper::findNearestHotel(
        $request->end_lat, 
        $request->end_lng
    );
    
    $route = Jalan::findRoute($start->PointID, $end->PointID);
    
    return response()->json([
        'start_hotel' => $start,
        'end_hotel' => $end,
        'route' => $route,
    ]);
}
```

---

### Phase 6: Spatial Search (Priority: MEDIUM)
**Goal:** Radius search & polygon search

#### 6.1 Radius Search
```php
public function radiusSearch(Request $request)
{
    $lat = $request->lat;
    $lng = $request->lng;
    $radius = $request->radius ?? 5; // km
    
    $hotels = ObjekPoint::selectRaw("
        *,
        (6371 * acos(
            cos(radians(?)) * 
            cos(radians(Latitude)) * 
            cos(radians(Longitude) - radians(?)) + 
            sin(radians(?)) * 
            sin(radians(Latitude))
        )) AS distance
    ", [$lat, $lng, $lat])
    ->having('distance', '<=', $radius)
    ->orderBy('distance')
    ->get();
    
    return response()->json($hotels);
}
```

#### 6.2 Polygon Search (Frontend with Turf.js)
```javascript
// Install Turf.js
npm install @turf/turf

// Check if point is in polygon
import * as turf from '@turf/turf';

const point = turf.point([longitude, latitude]);
const polygon = turf.polygon(kecamatanPolygon);
const isInside = turf.booleanPointInPolygon(point, polygon);
```

---

### Phase 7: File Upload (Priority: LOW)
**Goal:** Upload hotel images

#### 7.1 Update Hotel Form
```html
<form enctype="multipart/form-data">
    <input type="file" name="images[]" multiple>
</form>
```

#### 7.2 Handle Upload
```php
public function store(Request $request)
{
    $hotel = ObjekPoint::create($request->validated());
    
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('hotels', 'public');
            
            ObjekPointImage::create([
                'PointID' => $hotel->PointID,
                'ImageURL' => $path,
            ]);
        }
    }
    
    return redirect()->route('admin.hotels.index');
}
```

---

### Phase 8: UI Polish (Priority: LOW)
**Goal:** Better UX with Alpine.js

#### 8.1 Modal Components
```html
<!-- Create/Edit Modal -->
<div x-data="{ open: false }" x-show="open">
    <!-- Modal content -->
</div>
```

#### 8.2 Loading States
```html
<button 
    x-data="{ loading: false }"
    @click="loading = true"
    :disabled="loading"
>
    <span x-show="!loading">Save</span>
    <span x-show="loading">Saving...</span>
</button>
```

---

### Phase 9: Export & Documentation (Priority: HIGH)
**Goal:** Prepare for submission

#### 9.1 Export Database
In SSMS:
1. Right-click `hisurabaya_db`
2. Tasks → Generate Scripts
3. Choose "Schema and data"
4. Save as `hisurabaya_db.sql`

#### 9.2 Create Laporan (Report)
**Structure (5-10 pages):**
1. Cover page
2. Deskripsi sistem
3. CDM diagram (use dbdiagram.io)
4. Penjelasan fitur:
   - Search non-spasial
   - Search spasial
5. Screenshots (map, CRUD, search results)
6. Teknologi yang digunakan
7. Kesimpulan

#### 9.3 Create Presentation Slides
**Topics:**
- Problem statement
- Solution overview
- Database design (CDM)
- Key features demo
- Tech stack
- Live demo (if possible)

---

## 🚀 Quick Start Commands

```powershell
# Generate controller
php artisan make:controller ControllerName

# Generate migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# List routes
php artisan route:list

# Tinker (database console)
php artisan tinker

# Run development server
php artisan serve

# Watch for asset changes
npm run dev
```

---

## 📝 Testing Checklist

Before submission, test:
- ✅ Database migrations run without errors
- ✅ Seeders populate data correctly
- ✅ Login/Register works
- ✅ Google OAuth works (optional)
- ✅ Map displays hotels
- ✅ Filters work correctly
- ✅ CRUD operations work (create, edit, delete hotel)
- ✅ Search (non-spatial) returns correct results
- ✅ Search (spatial) returns correct results
- ✅ Route mode displays polyline
- ✅ Area mode displays polygons
- ✅ No errors in browser console
- ✅ Responsive design works on mobile

---

## 📚 Useful Resources

- Laravel Docs: https://laravel.com/docs/12.x
- Leaflet Docs: https://leafletjs.com/
- Turf.js Docs: https://turfjs.org/
- Tailwind CSS: https://tailwindcss.com/
- Alpine.js: https://alpinejs.dev/
- SQL Server: https://learn.microsoft.com/sql/

---

**Remember:** Start with Phase 1 (Map Integration) as it's the core feature! Good luck! 🚀
