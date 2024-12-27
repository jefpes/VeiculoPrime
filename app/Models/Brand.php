<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \App\Models\VehicleModel $models
 * @method \App\Models\VehicleModel models()
 * @property string $id
 * @property string $name
 */
class Brand extends Model
{
    use HasUlids;
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
