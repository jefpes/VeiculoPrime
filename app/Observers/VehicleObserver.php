<?php

namespace App\Observers;

use App\Models\Vehicle;

class VehicleObserver
{
    /**
     * Handle the Vehicle "created" event.
     */
    public function creating(Vehicle $vehicle): void
    {
        if ($vehicle->promotional_price <= 0) {
            $vehicle->promotional_price = null;
        }
    }

    /**
     * Handle the Vehicle "updated" event.
     */
    public function updating(Vehicle $vehicle): void
    {
        if ($vehicle->promotional_price <= 0) {
            $vehicle->promotional_price = null;
        }
    }

    /**
     * Handle the Vehicle "deleting" event.
     */
    public function deleting(Vehicle $vehicle): void
    {
        if ($vehicle->extras()->exists()) {
            $vehicle->extras()->detach();
        }

        if ($vehicle->accessories()->exists()) {
            $vehicle->accessories()->detach();
        }

        if ($vehicle->photos()->exists()) { //@phpstan-ignore-line
            foreach ($vehicle->photos as $photo) { //@phpstan-ignore-line
                $photo->delete();
            }
        }
    }

    /**
     * Handle the Vehicle "restored" event.
     */
    public function restored(Vehicle $vehicle): void
    {
        //
    }

    /**
     * Handle the Vehicle "force deleted" event.
     */
    public function forceDeleted(Vehicle $vehicle): void
    {
        //
    }
}
