<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \App\Models\VehicleModel $models
 * @method \App\Models\VehicleModel models()
 * @property string $id
 * @property string $name
 */
class Brand extends BaseModel
{
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
