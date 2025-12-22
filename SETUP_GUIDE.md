# SETUP GUIDE - HISurabaya Project

## 🚀 Quick Start (Ringkasan Cepat)

### Yang Sudah Dibuat ✅
1. ✅ Laravel 12 + Breeze (Tailwind + Alpine.js)
2. ✅ Laravel Socialite (untuk Google OAuth)
3. ✅ 7 Database migrations (users, kecamatan, objekpoint, dll)
4. ✅ 5 Eloquent models dengan relationships
5. ✅ 7 Database seeders dengan sample data
6. ✅ .env configured untuk SQL Server

### Yang Perlu Dilakukan ⚠️

#### 1. Install SQL Server PHP Drivers
**PENTING:** Laravel tidak bisa connect ke SQL Server tanpa extension ini!

**Download:**
- Kunjungi: https://docs.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
- Download versi yang sesuai dengan PHP Anda (PHP 8.2 or 8.3)

**Install:**
1. Extract file .zip
2. Copy `php_sqlsrv_82_ts_x64.dll` dan `php_pdo_sqlsrv_82_ts_x64.dll` ke folder PHP extensions
   - Contoh: `C:\php\ext\` atau `C:\xampp\php\ext\`
3. Edit `php.ini` dan tambahkan:
   ```ini
   extension=php_sqlsrv_82_ts_x64.dll
   extension=php_pdo_sqlsrv_82_ts_x64.dll
   ```
4. Restart web server/terminal
5. Verify dengan: `php -m | Select-String sql`

#### 2. Setup SQL Server Database

**Buka SQL Server Management Studio (SSMS):**

```sql
-- Create database
CREATE DATABASE hisurabaya_db;
GO

-- Verify
SELECT name FROM sys.databases WHERE name = 'hisurabaya_db';
```

**Update `.env` file:**
```env
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=hisurabaya_db
DB_USERNAME=sa
DB_PASSWORD=YOUR_SQL_SERVER_PASSWORD_HERE
```

⚠️ **PENTING:** Ganti `YOUR_SQL_SERVER_PASSWORD_HERE` dengan password SQL Server Anda!

#### 3. Run Migrations & Seeders

```powershell
# Masuk ke folder project
cd d:\Downloads\traveloke\hisurabaya

# Install dependencies (jika belum)
composer install
npm install

# Run migrations (create tables)
php artisan migrate

# Run seeders (insert sample data)
php artisan db:seed
```

**Expected Output:**
```
INFO  Running migrations.
✓ 0001_01_01_000000_create_users_table
✓ 2024_01_01_000001_create_kecamatan_table
✓ 2024_01_01_000002_create_facility_types_table
✓ 2024_01_01_000003_create_objekpoint_table
✓ 2024_01_01_000004_create_objekpoint_images_table
✓ 2024_01_01_000005_create_hotel_facilities_table
✓ 2024_01_01_000006_create_jalan_table
```

#### 4. Build & Run Application

```powershell
# Build frontend assets
npm run build

# Or untuk development dengan hot reload:
npm run dev

# Di terminal lain, start Laravel server
php artisan serve
```

**Aplikasi akan berjalan di:** http://localhost:8000

---

## 📊 Sample Data Overview

Setelah seeding, database akan berisi:

### Users (4 users)
- **admin@hisurabaya.com** (password: password) - Role: Admin
- **owner1@hisurabaya.com** (password: password) - Role: Owner  
- **owner2@hisurabaya.com** (password: password) - Role: Owner
- **user@hisurabaya.com** (password: password) - Role: User

### Kecamatan (31 kecamatan)
Terbagi dalam 5 wilayah:
- Surabaya Barat (7 kecamatan)
- Surabaya Timur (7 kecamatan)
- Surabaya Utara (5 kecamatan)
- Surabaya Selatan (8 kecamatan)
- Surabaya Tengah (4 kecamatan)

### Hotels (10 hotels)
Sample hotels dengan koordinat real:
1. Hotel Majapahit Surabaya (5★) - Genteng
2. Shangri-La Hotel Surabaya (5★) - Gubeng
3. JW Marriott Hotel Surabaya (5★) - Tegalsari
4. Vasa Hotel Surabaya (4★) - Sukolilo
5. Grand Mercure Surabaya City (4★) - Bubutan
6. Swiss-Belinn Tunjungan (3★) - Genteng
7. Hotel Ciputra World Surabaya (5★) - Tegalsari
8. Favehotel PGS Surabaya (3★) - Genteng
9. Pop! Hotel Gubeng Surabaya (2★) - Gubeng
10. The Westin Surabaya (5★) - Tegalsari

### Facilities (24 types)
WiFi, Parkir, AC, TV, Kolam Renang, Restoran, Gym, Spa, dll.

### Routes (6 routes)
Sample bidirectional routes antar hotel dengan jarak.

---

## 🛠️ Troubleshooting

### Problem: "could not find driver" error
**Solution:** Install SQL Server PHP drivers (lihat step 1 di atas)

### Problem: "SQLSTATE[08001]: Login failed"
**Solution:** 
1. Check SQL Server service is running:
   ```powershell
   Get-Service MSSQL* | Where-Object {$_.Status -eq "Running"}
   ```
2. Verify username/password di `.env`
3. Check SQL Server allows SQL Server Authentication (not Windows only)

### Problem: "Database does not exist"
**Solution:**
```sql
-- Di SSMS, run:
CREATE DATABASE hisurabaya_db;
```

### Problem: NPM build errors
**Solution:**
```powershell
# Clear cache and reinstall
Remove-Item node_modules -Recurse -Force
Remove-Item package-lock.json
npm install
npm run build
```

### Problem: Migration errors
**Solution:**
```powershell
# Reset database (WARNING: menghapus semua data!)
php artisan migrate:fresh --seed
```

---

## 📱 Testing the Application

### 1. Test Login
1. Visit: http://localhost:8000/login
2. Login dengan: admin@hisurabaya.com / password
3. Should redirect to dashboard

### 2. Test Database
```powershell
# Enter tinker
php artisan tinker

# Test queries
> \App\Models\User::count()
=> 4

> \App\Models\ObjekPoint::count()
=> 10

> \App\Models\Kecamatan::where('Wilayah', 'Surabaya Timur')->count()
=> 7

> \App\Models\ObjekPoint::with('kecamatan')->first()->kecamatan->NamaKecamatan
=> "Genteng"
```

### 3. Check Routes
```powershell
php artisan route:list
```

---

## 🎯 Next Development Steps

### Priority 1: Core Features
1. **Create MapController** untuk 3 map modes
2. **Integrate Leaflet.js** di view
3. **Hotel CRUD** - Admin/Owner can manage hotels
4. **Search & Filter** - Implement filtering

### Priority 2: Advanced Features  
5. **Google OAuth** - Social login
6. **Route Mode** - Calculate nearest hotel & show route
7. **Spatial Search** - Radius search (haversine)
8. **File Upload** - Hotel images

### Priority 3: Polish
9. **UI/UX** - Alpine.js modals, loading states
10. **Documentation** - Export .sql, create laporan

---

## 📚 Helpful Laravel Commands

```powershell
# Create new controller
php artisan make:controller MapController

# Create new migration
php artisan make:migration add_column_to_table

# Create new seeder
php artisan make:seeder NewSeeder

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check logs
Get-Content storage\logs\laravel.log -Tail 50

# Database console
php artisan tinker
```

---

## 📞 Support

Jika ada masalah saat setup:
1. Check error di `storage/logs/laravel.log`
2. Google error message
3. Check Laravel documentation: https://laravel.com/docs/12.x
4. Check SQL Server connection: https://learn.microsoft.com/sql/

---

**Good luck with your project! 🚀**
