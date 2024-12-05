<?php

namespace App\Models;

use App\Traits\{BelongsToTenantTrait, TenantScopeTrait};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \App\Models\VehicleModel $models
 * @method \App\Models\VehicleModel models()
 * @property int $id
 * @property string $name
 */
class Brand extends Model
{
    use HasFactory;
    use TenantScopeTrait;
    use BelongsToTenantTrait;

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
