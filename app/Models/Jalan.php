<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jalan extends Model
{
    protected $table = 'jalan';
    protected $primaryKey = 'JalanID';
    
    protected $fillable = [
        'NamaJalan',
        'KoordinatJSON',
        'StartPointID',
        'EndPointID',
        'DistanceKM',
    ];
    
    protected $casts = [
        'KoordinatJSON' => 'array',
        'DistanceKM' => 'decimal:2',
    ];
    
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'JalanID';
    }
    
    /**
     * Get the starting hotel
     */
    public function startPoint(): BelongsTo
    {
        return $this->belongsTo(ObjekPoint::class, 'StartPointID', 'PointID');
    }
    
    /**
     * Get the ending hotel
     */
    public function endPoint(): BelongsTo
    {
        return $this->belongsTo(ObjekPoint::class, 'EndPointID', 'PointID');
    }
    
    /**
     * Find route between two points (bidirectional)
     */
    public static function findRoute($pointA, $pointB)
    {
        return static::where(function($query) use ($pointA, $pointB) {
            $query->where('StartPointID', $pointA)
                  ->where('EndPointID', $pointB);
        })->orWhere(function($query) use ($pointA, $pointB) {
            $query->where('StartPointID', $pointB)
                  ->where('EndPointID', $pointA);
        })->first();
    }
}
