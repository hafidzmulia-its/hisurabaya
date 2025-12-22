<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';
    protected $primaryKey = 'KecamatanID';
    
    protected $fillable = [
        'NamaKecamatan',
        'Wilayah',
        'PolygonJSON',
    ];
    
    protected $casts = [
        'PolygonJSON' => 'array',
    ];
    
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'KecamatanID';
    }
    
    /**
     * Get hotels in this kecamatan
     */
    public function hotels(): HasMany
    {
        return $this->hasMany(ObjekPoint::class, 'KecamatanID', 'KecamatanID');
    }
}
