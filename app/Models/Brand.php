<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Brand
 * @package App\Models
 *
 * @method HasMany models()
 * @method BelongsTo tenant()
 *
 * @property \App\Models\VehicleModel $models
 * @property \App\Models\Tenant $tenant
 *
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 */
class Brand extends BaseModel
{
    use HasUlids;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
