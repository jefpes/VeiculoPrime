<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Class VehicleDocPhoto
 *
 * @property \App\Models\Vehicle $vehicle
 *
 * @method \App\Models\Vehicle vehicle()
 *
 * @property int $id
 * @property int $vehicle_id
 * @property string $path
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class VehicleDocPhoto extends BasePhoto
{
    protected function getPhotoDirectory(): string
    {
        return 'vehicle_doc_photos';
    }

    protected function getPhotoNamePrefix(): string
    {
        return Str::slug($this->vehicle->plate);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
