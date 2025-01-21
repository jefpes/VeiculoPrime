<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class VehicleType
 *
 * @method HasMany models()
 * @method BelongsTo tenant()
 *
 * @property \App\Models\VehicleModel $models
 * @property \App\Models\Tenant $tenant
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class VehicleType extends BaseModel
{
    use HasUlids;
    use HasFactory;

    protected $table = 'vehicle_types';

    protected $fillable = [
        'tenant_id',
        'name',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
