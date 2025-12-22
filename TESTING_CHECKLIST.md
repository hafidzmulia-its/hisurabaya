# 🧪 TESTING CHECKLIST

## Before You Start

```powershell
cd d:\Downloads\traveloke\hisurabaya
```

---

## ✅ Step 1: Database Migration

```powershell
# Test database connection
php artisan db:show
```

**Expected Output:**
```
Microsoft SQL Server .......................... DESKTOP-19DFUNE\SQLEXPRESS
Database ........................................... db_hisurabaya
```

If connection works:
```powershell
# Run migrations
php artisan migrate
```

**Expected Output:**
```
✓ 0001_01_01_000000_create_users_table
✓ 0001_01_01_000001_create_cache_table
✓ 0001_01_01_000002_create_jobs_table
✓ 2024_01_01_000001_create_kecamatan_table
✓ 2024_01_01_000002_create_facility_types_table
✓ 2024_01_01_000003_create_objekpoint_table
✓ 2024_01_01_000004_create_objekpoint_images_table
✓ 2024_01_01_000005_create_hotel_facilities_table
✓ 2024_01_01_000006_create_jalan_table
```

---

## ✅ Step 2: Seed Sample Data

```powershell
php artisan db:seed
```

**Expected Output:**
```
Seeding: Database\Seeders\UserSeeder
Seeding: Database\Seeders\KecamatanSeeder
Seeding: Database\Seeders\FacilityTypeSeeder
Seeding: Database\Seeders\ObjekPointSeeder
Seeding: Database\Seeders\ObjekPointImageSeeder
Seeding: Database\Seeders\HotelFacilitySeeder
Seeding: Database\Seeders\JalanSeeder
```

---

## ✅ Step 3: Verify Data in Tinker

```powershell
php artisan tinker
```

In tinker, test these:
```php
// Test 1: Count users
\App\Models\User::count()
// Expected: 4

// Test 2: Count hotels
\App\Models\ObjekPoint::count()
// Expected: 10

// Test 3: Count kecamatan
\App\Models\Kecamatan::count()
// Expected: 31

// Test 4: Get a hotel with relationships
$hotel = \App\Models\ObjekPoint::with('kecamatan', 'images', 'facilities')->first();
$hotel->NamaObjek
// Expected: "Hotel Majapahit Surabaya"

$hotel->kecamatan->NamaKecamatan
// Expected: "Genteng"

$hotel->images->count()
// Expected: 3

$hotel->facilities->count()
// Expected: 23

// Test 5: Test scope methods
\App\Models\ObjekPoint::active()->count()
// Expected: 10

\App\Models\ObjekPoint::starClass(5)->count()
// Expected: 5

// Exit tinker
exit
```

---

## ✅ Step 4: Build Assets

```powershell
npm install
npm run build
```

**Expected Output:**
```
✓ built in XXXXms
```

---

## ✅ Step 5: Start Server

```powershell
php artisan serve
```

**Expected Output:**
```
INFO  Server running on [http://127.0.0.1:8000].
```

**Keep this terminal open!**

---

## ✅ Step 6: Test in Browser

### Test 1: Homepage
- URL: http://localhost:8000
- Expected: Laravel welcome page ✅

### Test 2: Login Page
- URL: http://localhost:8000/login
- Expected: Login form with "Login with Google" button ✅

### Test 3: Login with Test Account
- Email: `admin@hisurabaya.com`
- Password: `password`
- Expected: Redirect to dashboard ✅

### Test 4: View Map
- URL: http://localhost:8000/map
- Expected: 
  - OpenStreetMap visible ✅
  - 10 hotel markers on map ✅
  - Filter panel on right side ✅
  - Centered on Surabaya ✅

### Test 5: Click Hotel Marker
- Click any marker
- Expected: Popup with hotel details:
  - Hotel name
  - Address
  - Star rating
  - Kecamatan & wilayah
  - Price range ✅

### Test 6: Test Name Filter
- In filter panel, type: "Majapahit"
- Click "Apply Filters"
- Expected: Only 1 marker (Hotel Majapahit) ✅

### Test 7: Test Star Filter
- Select: "5 Stars"
- Click "Apply Filters"
- Expected: 5 markers (5-star hotels only) ✅

### Test 8: Test Wilayah Filter
- Select: "Surabaya Timur"
- Click "Apply Filters"
- Expected: Fewer markers (hotels in East Surabaya) ✅

### Test 9: Test Price Filter
- Min: 1000000
- Max: 2000000
- Click "Apply Filters"
- Expected: Mid-range hotels only ✅

### Test 10: Test Reset
- Click "Reset" button
- Expected: All 10 markers show again ✅

### Test 11: Test Route Mode
- Change mode to "Route Mode"
- Expected: Blue polylines show routes between hotels ✅

### Test 12: Test Area Mode
- Change mode to "Area Mode"
- Select a wilayah
- Expected: Colored polygon boundaries (if polygon data available) ✅

---

## ✅ Step 7: Test API Endpoints

Open new terminal and test:

```powershell
# Test hotels API
curl http://localhost:8000/api/hotels

# Test with filters
curl "http://localhost:8000/api/hotels?stars=5"
curl "http://localhost:8000/api/hotels?wilayah=Surabaya Timur"
curl "http://localhost:8000/api/hotels?name=Majapahit"

# Test kecamatan API
curl http://localhost:8000/api/kecamatan

# Test routes API
curl http://localhost:8000/api/routes

# Test radius search (5km around center of Surabaya)
curl "http://localhost:8000/api/radius-search?lat=-7.2575&lng=112.7521&radius=5"
```

**Expected:** JSON GeoJSON format responses ✅

---

## ✅ Step 8: Check Browser Console

In map page:
1. Press F12 (open developer tools)
2. Go to Console tab
3. Expected: No red errors ✅

---

## ✅ Step 9: Check Database in SSMS

```sql
USE db_hisurabaya;

-- View all tables
SELECT TABLE_NAME 
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_TYPE = 'BASE TABLE';

-- Count records
SELECT 'users' as [Table], COUNT(*) as [Count] FROM users
UNION ALL
SELECT 'kecamatan', COUNT(*) FROM kecamatan
UNION ALL
SELECT 'objekpoint', COUNT(*) FROM objekpoint
UNION ALL
SELECT 'facility_types', COUNT(*) FROM facility_types
UNION ALL
SELECT 'objekpoint_images', COUNT(*) FROM objekpoint_images
UNION ALL
SELECT 'hotel_facilities', COUNT(*) FROM hotel_facilities
UNION ALL
SELECT 'jalan', COUNT(*) FROM jalan;

-- View hotels with details
SELECT TOP 5
    o.NamaObjek,
    o.StarClass,
    o.HargaMin,
    o.HargaMax,
    k.NamaKecamatan,
    k.Wilayah
FROM objekpoint o
LEFT JOIN kecamatan k ON o.KecamatanID = k.KecamatanID;
```

---

## 🎯 Success Criteria

Your project is fully working if:

- [x] All migrations ran without errors
- [x] All seeders completed successfully
- [x] Database has 4 users, 31 kecamatan, 10 hotels
- [x] Can login at /login
- [x] Map shows at /map
- [x] Markers display correctly
- [x] Popups show hotel details
- [x] Filters work (name, stars, wilayah, price)
- [x] API endpoints return GeoJSON
- [x] No console errors
- [x] No Laravel errors in storage/logs/laravel.log

---

## ⚠️ If Something Fails

### Error: "could not find driver"
**Fix:** Install SQL Server PHP extension (see SETUP_GUIDE.md)

### Error: "Login failed"
**Fix:** Check database credentials in .env

### Error: "Database does not exist"
**Fix:** Create database in SSMS:
```sql
CREATE DATABASE db_hisurabaya;
```

### Error: "Migration failed"
**Fix:** Reset and re-run:
```powershell
php artisan migrate:fresh --seed
```

### Map not showing markers
**Fix:** 
1. Check browser console for errors
2. Check that seeders ran successfully
3. Visit API directly: http://localhost:8000/api/hotels

### Filters not working
**Fix:**
1. Clear browser cache
2. Make sure npm run build was successful
3. Check browser console for JavaScript errors

---

## 📊 Testing Summary Table

| Test | Command/URL | Expected Result | Status |
|------|-------------|-----------------|--------|
| DB Connect | `php artisan db:show` | Shows DB info | ☐ |
| Migrate | `php artisan migrate` | 7 migrations run | ☐ |
| Seed | `php artisan db:seed` | 7 seeders run | ☐ |
| Tinker Count | `User::count()` | Returns 4 | ☐ |
| Login | /login | Login form shows | ☐ |
| Auth | admin@hisurabaya.com | Login success | ☐ |
| Map | /map | Map with 10 markers | ☐ |
| Popup | Click marker | Hotel details show | ☐ |
| Filter Name | Search "Majapahit" | 1 marker | ☐ |
| Filter Stars | Select "5 Stars" | 5 markers | ☐ |
| API Hotels | /api/hotels | GeoJSON response | ☐ |
| Route Mode | Change mode | Blue lines show | ☐ |
| No Errors | F12 Console | No red errors | ☐ |

---

## 🎉 When All Tests Pass

You're ready for:
1. ✅ Demo to instructor
2. ✅ Export database (.sql)
3. ✅ Create laporan (report)
4. ✅ Prepare presentation slides
5. ✅ Optional: Add hotel CRUD

---

**Need help? Check:**
- MIGRATION_GUIDE.md
- SETUP_GUIDE.md
- GOOGLE_OAUTH_SETUP.md
- storage/logs/laravel.log
