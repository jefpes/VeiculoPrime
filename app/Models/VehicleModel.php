<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Class VehicleModel
 *
 * @method BelongsTo brand()
 * @method HasMany vehicles()
 * @method BelongsTo type()
 * @method BelongsTo tenant()
 *
 * @property \App\Models\Brand $brand
 * @property \App\Models\Vehicle $vehicles
 * @property \App\Models\VehicleType $type
 * @property \App\Models\Tenant $tenant
 * @property string $id
 * @property string $tenant_id
 * @property string $vehicle_type_id
 * @property string $brand_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class VehicleModel extends BaseModel
{
    use HasUlids;
    use HasFactory;

    protected $table = 'vehicle_models';

    protected $fillable = [
        'tenant_id',
        'name',
        'brand_id',
        'vehicle_type_id',
    ];

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
