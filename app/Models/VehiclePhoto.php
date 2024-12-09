<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Class VehiclePhoto
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
class VehiclePhoto extends BasePhoto
{
    protected function getPhotoDirectory(): string
    {
        return 'vehicle_photos';
    }

    protected function getPhotoNamePrefix(): string
    {
        return sprintf(
            '%s_%s_%s',
            Str::slug($this->vehicle->model->name),
            $this->vehicle->year_one,
            Str::slug($this->vehicle->color)
        );
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
