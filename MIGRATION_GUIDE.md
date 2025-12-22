# MIGRATION & TESTING GUIDE

## 🗄️ Step 1: Verify SQL Server Connection

### Option A: Test with SQL Server Authentication (Recommended)
```powershell
# In SSMS, create a SQL Server login
CREATE LOGIN hisurabaya_user WITH PASSWORD = 'YourStrongPassword123!';
CREATE USER hisurabaya_user FOR LOGIN hisurabaya_user;
USE db_hisurabaya;
EXEC sp_addrolemember 'db_owner', 'hisurabaya_user';
```

Then update .env:
```env
DB_USERNAME=hisurabaya_user
DB_PASSWORD=YourStrongPassword123!
```

### Option B: Continue with Windows Authentication (Current Setup)
Your current .env uses Windows Authentication. Make sure:
1. SQL Server is running
2. Your Windows user has permissions

---

## 🚀 Step 2: Run Migrations

```powershell
cd d:\Downloads\traveloke\hisurabaya

# Test database connection first
php artisan db:show

# If connection works, run migrations
php artisan migrate

# Expected output:
# ✓ 0001_01_01_000000_create_users_table
# ✓ 2024_01_01_000001_create_kecamatan_table
# ✓ 2024_01_01_000002_create_facility_types_table
# ✓ 2024_01_01_000003_create_objekpoint_table
# ✓ 2024_01_01_000004_create_objekpoint_images_table
# ✓ 2024_01_01_000005_create_hotel_facilities_table
# ✓ 2024_01_01_000006_create_jalan_table
```

---

## 📊 Step 3: Seed Sample Data

```powershell
# Run all seeders
php artisan db:seed

# Expected output:
# Seeding: Database\Seeders\UserSeeder
# Seeding: Database\Seeders\KecamatanSeeder
# Seeding: Database\Seeders\FacilityTypeSeeder
# Seeding: Database\Seeders\ObjekPointSeeder
# Seeding: Database\Seeders\ObjekPointImageSeeder
# Seeding: Database\Seeders\HotelFacilitySeeder
# Seeding: Database\Seeders\JalanSeeder
```

---

## 🧪 Step 4: Test the Setup

### Test 1: Check Database Tables
```powershell
php artisan tinker

# In tinker, run:
\App\Models\User::count()
# Should return: 4

\App\Models\ObjekPoint::count()
# Should return: 10

\App\Models\Kecamatan::count()
# Should return: 31

# Get a hotel with relationships
\App\Models\ObjekPoint::with('kecamatan', 'images', 'facilities')->first()
# Should show hotel with kecamatan name, images, and facilities

# Exit tinker
exit
```

### Test 2: Check in SSMS
```sql
USE db_hisurabaya;

-- Check all tables exist
SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE';

-- Check data
SELECT COUNT(*) FROM users;           -- Should be 4
SELECT COUNT(*) FROM kecamatan;       -- Should be 31
SELECT COUNT(*) FROM objekpoint;      -- Should be 10
SELECT COUNT(*) FROM facility_types;  -- Should be 24
SELECT COUNT(*) FROM jalan;           -- Should be 6

-- Check a hotel with details
SELECT 
    o.NamaObjek,
    o.Latitude,
    o.Longitude,
    k.NamaKecamatan,
    k.Wilayah
FROM objekpoint o
LEFT JOIN kecamatan k ON o.KecamatanID = k.KecamatanID;
```

### Test 3: Start Laravel Server
```powershell
# Build frontend assets
npm run build

# Start server
php artisan serve

# Visit: http://localhost:8000
```

### Test 4: Try Logging In
1. Visit: http://localhost:8000/login
2. Use these credentials:
   - Email: admin@hisurabaya.com
   - Password: password
3. Should redirect to dashboard

---

## ⚠️ Troubleshooting

### Error: "could not find driver"
**Solution:** Install SQL Server PHP extension
```powershell
# Check if extensions are loaded
php -m | Select-String sql

# Should show:
# pdo_sqlsrv
# sqlsrv
```

If not showing, download from:
https://docs.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server

### Error: "Login failed for user"
**Solution 1:** Use SQL Server Authentication (Option A above)

**Solution 2:** Grant permissions to Windows user
```sql
USE db_hisurabaya;
EXEC sp_grantdbaccess 'DESKTOP-19DFUNE\hikmatullah';
EXEC sp_addrolemember 'db_owner', 'hikmatullah';
```

### Error: "Database does not exist"
**Solution:** Create it in SSMS
```sql
CREATE DATABASE db_hisurabaya;
```

### Error: Migration fails
**Solution:** Reset and try again
```powershell
# Drop all tables and re-migrate
php artisan migrate:fresh --seed
```

---

## ✅ Success Checklist

After successful setup, you should have:
- [x] All 7 tables created in SQL Server
- [x] 4 users (admin, 2 owners, 1 user)
- [x] 31 kecamatan records
- [x] 10 hotel records
- [x] 24 facility types
- [x] Can login at http://localhost:8000/login
- [x] No errors in storage/logs/laravel.log

---

## 🎯 Next: Once Migration Works
1. ✅ Set up Google OAuth (see GOOGLE_OAUTH_SETUP.md)
2. ✅ Build Map Interface (Phase 1)
3. ✅ Add CRUD for hotels (Phase 2)
