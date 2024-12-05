<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Class VehicleModel
 *
 * @property \App\Models\Brand $brand
 * @property \App\Models\Vehicle $vehicles
 * @property \App\Models\VehicleType $type
 *
 * @method \App\Models\Brand brand()
 * @method \App\Models\Vehicle vehicles()
 * @method \App\Models\VehicleType type()
 *
 * @property int $id
 * @property int $vehicle_type_id
 * @property int $brand_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class VehicleModel extends BaseModel
{
    use HasFactory;

    protected $table = 'vehicle_models';

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id', 'id');
    }
}
