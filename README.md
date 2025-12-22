# HISurabaya - Hotel Information System Surabaya
## GIS Database Project with Laravel 12 & SQL Server

### 📋 Project Overview
HISurabaya adalah aplikasi Sistem Informasi Geografis (GIS) untuk manajemen dan pencarian hotel di Surabaya. Proyek ini menggunakan Laravel 12, SQL Server, dan OpenStreetMap untuk menampilkan data hotel secara geografis.

### 🎯 Key Features
- ✅ **3 Map Modes:**
  - Point Mode: Tampilkan hotel dengan filter (harga, rating, wilayah, kecamatan)
  - Route Mode: Tampilkan rute antar hotel dengan jarak
  - Area Mode: Tampilkan polygon kecamatan
  
- ✅ **Search Features:**
  - Non-spatial: Cari berdasarkan nama, kategori, wilayah
  - Spatial: Radius search (haversine) & polygon search (Turf.js)
  
- ✅ **CRUD Management:**
  - Hotel management (data, images, facilities)
  - Kecamatan polygons
  - Routes (jalan)
  
- ✅ **Authentication:**
  - Laravel Breeze (Login/Register)
  - Google OAuth
  - Role-based access (admin, owner, user)

### 🏗️ Database Structure (CDM)

#### 1. users
- id, name, username, email, password, google_id, role, timestamps

#### 2. kecamatan (Polygon Layer)
- KecamatanID, NamaKecamatan, Wilayah, PolygonJSON, timestamps

#### 3. objekpoint (Point Layer - Hotels)
- PointID, NamaObjek, Deskripsi, Alamat, Latitude, Longitude
- HargaMin, HargaMax, StarClass
- KecamatanID (FK), OwnerUserID (FK), IsActive, timestamps

#### 4. objekpoint_images
- ImageID, PointID (FK), ImageURL, timestamps

#### 5. facility_types
- FacilityID, Name, timestamps

#### 6. hotel_facilities (pivot table)
- PointID (FK), FacilityID (FK), IsAvailable, ExtraPrice, timestamps

#### 7. jalan (Line Layer - Routes)
- JalanID, NamaJalan, KoordinatJSON, StartPointID (FK), EndPointID (FK)
- DistanceKM, timestamps

### 🛠️ Tech Stack
- **Backend:** Laravel 12
- **Frontend:** Blade + Tailwind CSS + Alpine.js
- **Database:** Microsoft SQL Server
- **Map:** Leaflet.js + OpenStreetMap
- **Auth:** Laravel Breeze + Laravel Socialite (Google OAuth)
- **Geo Library:** Turf.js (untuk polygon search)

### 📦 Installation Guide

#### Prerequisites
1. PHP 8.2 or higher
2. Composer
3. Node.js & NPM
4. Microsoft SQL Server (SSMS Express)
5. SQL Server PHP Extension (sqlsrv, pdo_sqlsrv)

#### Step 1: Install SQL Server PHP Extension
```powershell
# Download and install SQL Server drivers for PHP
# https://docs.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server

# Enable extensions in php.ini
# extension=php_sqlsrv_82_ts_x64.dll
# extension=php_pdo_sqlsrv_82_ts_x64.dll
```

#### Step 2: Setup Database
1. Buka SQL Server Management Studio (SSMS)
2. Create database baru:
```sql
CREATE DATABASE hisurabaya_db;
```

3. Update `.env` file dengan credentials SQL Server Anda:
```env
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=hisurabaya_db
DB_USERNAME=sa
DB_PASSWORD=your_password_here
```

#### Step 3: Run Migrations & Seeders
```powershell
cd hisurabaya

# Install dependencies
composer install
npm install

# Generate app key (already done)
# php artisan key:generate

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

#### Step 4: (Optional) Setup Google OAuth
1. Buat project di [Google Cloud Console](https://console.cloud.google.com/)
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Update `.env`:
```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

#### Step 5: Build Assets & Run
```powershell
# Build frontend assets
npm run build

# Or run in development mode with hot reload
npm run dev

# In another terminal, start Laravel server
php artisan serve
```

Visit: http://localhost:8000

### 👤 Default Users
Setelah seeding, Anda dapat login dengan:

**Admin:**
- Email: admin@hisurabaya.com
- Password: password

**Owner:**
- Email: owner1@hisurabaya.com
- Password: password

**User:**
- Email: user@hisurabaya.com
- Password: password

### 📊 Sample Data
Database sudah terisi dengan:
- 31 Kecamatan di Surabaya (5 wilayah)
- 24 Jenis fasilitas hotel
- 10 Hotel sample dengan koordinat real
- Multiple hotel images (placeholder)
- Hotel-facility relationships
- 6 Sample routes antar hotel

### 🗺️ Map Implementation (Next Steps)
Leaflet.js akan diintegrasikan di views dengan fitur:
- Marker clustering untuk banyak hotel
- Popup dengan info hotel
- Filter panel (sidebar atau modal)
- Route visualization (polyline)
- Polygon boundaries (kecamatan)

### 📁 Project Structure
```
hisurabaya/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── HotelController.php (to be created)
│   │       ├── MapController.php (to be created)
│   │       └── Auth/GoogleController.php (to be created)
│   └── Models/
│       ├── User.php ✅
│       ├── Kecamatan.php ✅
│       ├── ObjekPoint.php ✅
│       ├── ObjekPointImage.php ✅
│       ├── FacilityType.php ✅
│       └── Jalan.php ✅
├── database/
│   ├── migrations/ ✅
│   └── seeders/ ✅
├── resources/
│   ├── views/
│   │   ├── dashboard/ (to be created)
│   │   ├── map/ (to be created)
│   │   └── hotels/ (to be created)
│   └── js/
│       └── map.js (to be created)
└── routes/
    └── web.php (to be updated)
```

### 🎯 Next Steps for Development
Untuk melanjutkan development:

1. **Setup Google OAuth** - Buat controller dan routes untuk Google login
2. **Create Controllers** - HotelController, MapController, KecamatanController, dll.
3. **Build Map Views** - Integrate Leaflet.js dengan Alpine.js
4. **Implement Filters** - Price range, star class, wilayah, search
5. **Route Mode** - Snap to nearest hotel + display polyline
6. **Area Mode** - Display kecamatan polygons
7. **Spatial Search** - Haversine formula + Turf.js polygon check
8. **CRUD Forms** - Modal forms dengan Alpine.js
9. **File Upload** - Upload hotel images
10. **Export & Documentation** - .sql export + laporan

### 📝 Laporan & Deliverables
Untuk tugas akhir, siapkan:
1. ✅ Database (.sql export dari SQL Server)
2. ✅ Source code aplikasi
3. CDM Diagram (bisa pakai tools seperti dbdiagram.io)
4. Laporan (5-10 halaman):
   - Deskripsi sistem
   - CDM & relasi tabel
   - Penjelasan fitur search spasial & non-spasial
   - Screenshot aplikasi dan map
5. Slide presentasi

### 🔧 Troubleshooting

#### SQL Server Connection Issues
```powershell
# Check if SQL Server is running
Get-Service MSSQL*

# Test connection with sqlcmd
sqlcmd -S localhost -U sa -P your_password
```

#### PHP Extension Issues
```powershell
# Check loaded extensions
php -m | Select-String sql

# Should show:
# pdo_sqlsrv
# sqlsrv
```

### 🤝 Credits
Developed for Database Technology final project
Subject: Teknologi Basis Data
Project: GIS Database + OpenStreetMap

### 📄 License
Educational Purpose Only
