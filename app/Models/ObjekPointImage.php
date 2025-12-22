<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjekPointImage extends Model
{
    protected $table = 'objekpoint_images';
    protected $primaryKey = 'ImageID';
    
    protected $fillable = [
        'PointID',
        'ImageURL',
    ];
    
    /**
     * Get the hotel that owns this image
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(ObjekPoint::class, 'PointID', 'PointID');
    }
}
