<?php

namespace App\Models;

use App\Traits\{BelongsToTenantTrait, TenantScopeTrait};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \App\Models\VehicleModel $models
 *
 * @method \App\Models\VehicleModel models()
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class VehicleType extends Model
{
    use HasFactory;
    use TenantScopeTrait;
    use BelongsToTenantTrait;

    protected $table = 'vehicle_types';

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
