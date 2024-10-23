<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read VehicleModel $models
 * @property-read int $id
 * @property-read string $name
 */
class Brand extends Model
{
    use HasFactory;

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
