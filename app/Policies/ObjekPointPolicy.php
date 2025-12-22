<?php

namespace App\Policies;

use App\Models\ObjekPoint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ObjekPointPolicy
{
    /**
     * Determine if the user can view any hotels.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view hotels list
        return true;
    }

    /**
     * Determine if the user can view the hotel.
     */
    public function view(User $user, ObjekPoint $objekPoint): bool
    {
        // All authenticated users can view a hotel
        return true;
    }

    /**
     * Determine if the user can create hotels.
     */
    public function create(User $user): bool
    {
        // Only admin and owner can create hotels
        return in_array($user->role, ['admin', 'owner']);
    }

    /**
     * Determine if the user can update the hotel.
     */
    public function update(User $user, ObjekPoint $objekPoint): bool
    {
        // Admin can update any hotel
        if ($user->role === 'admin') {
            return true;
        }
        
        // Owner can only update their own hotels
        if ($user->role === 'owner') {
            return (int) $objekPoint->OwnerUserID === (int) $user->id;
        }
        
        return false;
    }

    /**
     * Determine if the user can delete the hotel.
     */
    public function delete(User $user, ObjekPoint $objekPoint): bool
    {
        // Admin can delete any hotel
        if ($user->role === 'admin') {
            return true;
        }
        
        // Owner can only delete their own hotels
        if ($user->role === 'owner') {
            return (int) $objekPoint->OwnerUserID === (int) $user->id;
        }
        
        return false;
    }

    /**
     * Determine if the user can restore the hotel.
     */
    public function restore(User $user, ObjekPoint $objekPoint): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can permanently delete the hotel.
     */
    public function forceDelete(User $user, ObjekPoint $objekPoint): bool
    {
        return $user->role === 'admin';
    }
}
