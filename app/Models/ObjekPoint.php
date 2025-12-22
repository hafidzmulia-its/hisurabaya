<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ObjekPoint extends Model
{
    protected $table = 'objekpoint';
    protected $primaryKey = 'PointID';
    
    protected $fillable = [
        'NamaObjek',
        'Deskripsi',
        'Alamat',
        'Latitude',
        'Longitude',
        'HargaMin',
        'HargaMax',
        'StarClass',
        'KecamatanID',
        'OwnerUserID',
        'IsActive',
    ];
    
    protected $casts = [
        'Latitude' => 'decimal:8',
        'Longitude' => 'decimal:8',
        'HargaMin' => 'decimal:2',
        'HargaMax' => 'decimal:2',
        'StarClass' => 'integer',
        'OwnerUserID' => 'integer',
        'IsActive' => 'boolean',
    ];
    
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'PointID';
    }
    
    /**
     * Get the kecamatan that owns the hotel
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'KecamatanID', 'KecamatanID');
    }
    
    /**
     * Get the owner of the hotel
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'OwnerUserID');
    }
    
    /**
     * Get all images for this hotel
     */
    public function images(): HasMany
    {
        return $this->hasMany(ObjekPointImage::class, 'PointID', 'PointID');
    }
    
    /**
     * Get facilities for this hotel
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(
            FacilityType::class,
            'hotel_facilities',
            'PointID',
            'FacilityID'
        )
        ->withPivot('IsAvailable', 'ExtraPrice')
        ->withTimestamps();
    }
    
    /**
     * Get routes starting from this hotel
     */
    public function routesFrom(): HasMany
    {
        return $this->hasMany(Jalan::class, 'StartPointID', 'PointID');
    }
    
    /**
     * Get routes ending at this hotel
     */
    public function routesTo(): HasMany
    {
        return $this->hasMany(Jalan::class, 'EndPointID', 'PointID');
    }
    
    /**
     * Scope for active hotels only
     */
    public function scopeActive($query)
    {
        return $query->where('IsActive', true);
    }
    
    /**
     * Scope for filtering by price range
     */
    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('HargaMin', '>=', $min);
        }
        if ($max !== null) {
            $query->where('HargaMax', '<=', $max);
        }
        return $query;
    }
    
    /**
     * Scope for filtering by star class
     */
    public function scopeStarClass($query, $stars)
    {
        return $query->where('StarClass', $stars);
    }
    
    /**
     * Scope for filtering by wilayah
     */
    public function scopeWilayah($query, $wilayah)
    {
        return $query->whereHas('kecamatan', function($q) use ($wilayah) {
            $q->where('Wilayah', $wilayah);
        });
    }
}
