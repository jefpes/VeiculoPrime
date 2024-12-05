<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \App\Models\VehicleModel $models
 * @method \App\Models\VehicleModel models()
 * @property int $id
 * @property string $name
 */
class Brand extends BaseModel
{
    use HasFactory;

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
