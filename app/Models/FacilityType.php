<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityType extends Model
{
    protected $table = 'facility_types';
    protected $primaryKey = 'FacilityID';
    
    protected $fillable = [
        'Name',
    ];
    
    /**
     * Get hotels with this facility
     */
    public function hotels()
    {
        return $this->belongsToMany(
            ObjekPoint::class,
            'hotel_facilities',
            'FacilityID',
            'PointID'
        )
        ->withPivot('IsAvailable', 'ExtraPrice')
        ->withTimestamps();
    }
}
