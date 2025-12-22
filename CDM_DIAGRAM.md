# Conceptual Data Model (CDM) - HISurabaya

## Database Schema Diagram

```
┌─────────────────────────────────────┐
│ users                               │
├─────────────────────────────────────┤
│ PK │ id                            │
│    │ name                          │
│    │ username (unique, nullable)   │
│    │ email (unique)                │
│    │ email_verified_at             │
│    │ password (nullable)           │
│    │ google_id (unique, nullable)  │
│    │ role (enum: user/owner/admin) │
│    │ remember_token                │
│    │ created_at                    │
│    │ updated_at                    │
└─────────────────────────────────────┘
           │
           │ 1:N (OwnerUserID)
           ▼
┌─────────────────────────────────────┐
│ kecamatan                           │
├─────────────────────────────────────┤
│ PK │ KecamatanID                   │
│    │ NamaKecamatan                 │
│    │ Wilayah (enum: 5 wilayah)     │
│    │ PolygonJSON (GeoJSON)         │
│    │ created_at                    │
│    │ updated_at                    │
└─────────────────────────────────────┘
           │
           │ 1:N (KecamatanID)
           ▼
┌─────────────────────────────────────┐
│ objekpoint (Hotels)                 │
├─────────────────────────────────────┤
│ PK │ PointID                       │
│    │ NamaObjek                     │
│    │ Deskripsi                     │
│    │ Alamat                        │
│    │ Latitude (decimal 10,8)       │
│    │ Longitude (decimal 11,8)      │
│    │ HargaMin (decimal 12,2)       │
│    │ HargaMax (decimal 12,2)       │
│    │ StarClass (0-5)               │
│ FK │ KecamatanID                   │
│ FK │ OwnerUserID                   │
│    │ IsActive (boolean)            │
│    │ created_at                    │
│    │ updated_at                    │
└─────────────────────────────────────┘
     │                    │
     │ 1:N                │ N:M (via hotel_facilities)
     ▼                    ▼
┌──────────────────┐   ┌─────────────────────────────┐
│ objekpoint_      │   │ hotel_facilities (Pivot)    │
│ images           │   ├─────────────────────────────┤
├──────────────────┤   │ PK,FK │ PointID            │
│ PK │ ImageID    │   │ PK,FK │ FacilityID         │
│ FK │ PointID    │   │       │ IsAvailable        │
│    │ ImageURL   │   │       │ ExtraPrice         │
│    │ created_at │   │       │ created_at         │
│    │ updated_at │   │       │ updated_at         │
└──────────────────┘   └─────────────────────────────┘
                                  │
                                  │ N:M
                                  ▼
                       ┌─────────────────────────────┐
                       │ facility_types              │
                       ├─────────────────────────────┤
                       │ PK │ FacilityID            │
                       │    │ Name                  │
                       │    │ created_at            │
                       │    │ updated_at            │
                       └─────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│ jalan (Routes)                                      │
├─────────────────────────────────────────────────────┤
│ PK │ JalanID                                       │
│    │ NamaJalan                                     │
│    │ KoordinatJSON (GeoJSON LineString)           │
│ FK │ StartPointID (references objekpoint.PointID) │
│ FK │ EndPointID (references objekpoint.PointID)   │
│    │ DistanceKM (decimal 8,2)                     │
│    │ created_at                                    │
│    │ updated_at                                    │
└─────────────────────────────────────────────────────┘
```

## Relationships Summary

### 1. users → objekpoint (1:N)
- One user (owner) can own multiple hotels
- Foreign Key: `OwnerUserID` in objekpoint → `id` in users
- On Delete: SET NULL

### 2. kecamatan → objekpoint (1:N)
- One kecamatan contains multiple hotels
- Foreign Key: `KecamatanID` in objekpoint → `KecamatanID` in kecamatan
- On Delete: SET NULL

### 3. objekpoint → objekpoint_images (1:N)
- One hotel has multiple images
- Foreign Key: `PointID` in objekpoint_images → `PointID` in objekpoint
- On Delete: CASCADE (delete images when hotel deleted)

### 4. objekpoint ↔ facility_types (N:M via hotel_facilities)
- Many-to-many: Hotels can have multiple facilities, facilities can belong to multiple hotels
- Pivot table: `hotel_facilities`
- Additional pivot data: `IsAvailable`, `ExtraPrice`
- On Delete: CASCADE on both sides

### 5. objekpoint → jalan (1:N twice)
- One hotel can be start point of multiple routes
- One hotel can be end point of multiple routes
- Foreign Keys: 
  - `StartPointID` in jalan → `PointID` in objekpoint
  - `EndPointID` in jalan → `PointID` in objekpoint
- On Delete: SET NULL
- **Note:** Routes are BIDIRECTIONAL (A→B equals B→A)

## Layer Types

### Point Layer: objekpoint
- Represents: Hotel locations
- Geometry: Latitude, Longitude (separate columns)
- Display: Markers on map

### Line Layer: jalan
- Represents: Routes between hotels
- Geometry: GeoJSON LineString in `KoordinatJSON`
- Display: Polylines on map

### Polygon Layer: kecamatan
- Represents: Administrative boundaries (kecamatan areas)
- Geometry: GeoJSON Polygon in `PolygonJSON`
- Display: Filled polygons on map

## GeoJSON Format

### Point (stored as Lat/Long columns)
```json
{
  "Latitude": -7.2632,
  "Longitude": 112.7379
}
```

### LineString (stored in jalan.KoordinatJSON)
```json
{
  "type": "LineString",
  "coordinates": [
    [112.7379, -7.2632],
    [112.7419, -7.2649]
  ]
}
```

### Polygon (stored in kecamatan.PolygonJSON)
```json
{
  "type": "Polygon",
  "coordinates": [
    [
      [112.7, -7.25],
      [112.75, -7.25],
      [112.75, -7.28],
      [112.7, -7.28],
      [112.7, -7.25]
    ]
  ]
}
```

## Indexes

### Performance Indexes
- `objekpoint.Latitude, Longitude` - For spatial queries
- `objekpoint.KecamatanID` - For joins with kecamatan
- `objekpoint.IsActive` - For filtering active hotels
- `kecamatan.Wilayah` - For wilayah filtering
- `jalan.StartPointID, EndPointID` - For route queries

## Enumerations

### users.role
- `user` - Regular user (can view, search)
- `owner` - Hotel owner (can manage own hotels)
- `admin` - Administrator (full access)

### kecamatan.Wilayah
- `Surabaya Barat`
- `Surabaya Timur`
- `Surabaya Utara`
- `Surabaya Selatan`
- `Surabaya Tengah`

## Data Constraints

### objekpoint
- `StarClass`: Integer 0-5
- `HargaMin` ≤ `HargaMax`
- `Latitude`: -90 to 90
- `Longitude`: -180 to 180
- Surabaya coordinates approximately: 
  - Lat: -7.2 to -7.4
  - Long: 112.6 to 112.8

### jalan
- `DistanceKM` must be > 0
- Bidirectional: No duplicate pairs (A,B) and (B,A)

## Search Capabilities

### Non-Spatial Search
1. By name: `objekpoint.NamaObjek LIKE '%keyword%'`
2. By wilayah: JOIN with kecamatan, filter by `Wilayah`
3. By kecamatan: `objekpoint.KecamatanID = ?`
4. By price range: `HargaMin >= ? AND HargaMax <= ?`
5. By star class: `StarClass = ?`

### Spatial Search
1. **Radius Search (Haversine):**
   ```sql
   -- Find hotels within X km from point (lat, long)
   -- Using Haversine formula in SQL Server
   ```

2. **Polygon Search (Turf.js):**
   ```javascript
   // Client-side: Check if point is inside kecamatan polygon
   turf.booleanPointInPolygon(point, polygon)
   ```

## Export Notes

For `.sql` export from SQL Server:
```sql
-- Right-click database in SSMS
-- Tasks → Generate Scripts
-- Select specific tables or entire database
-- Include data in script
```
