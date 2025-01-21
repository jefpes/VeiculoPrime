<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Accessory
 *
 * @package App\Models
 *
 * @method BelongsToMany vehicle()
 *
 * @property \App\Models\Vehicle $vehicle
 * @property string $id
 * @property string $name
 * @property string $icon
 */
class Accessory extends Model
{
    use HasUlids;
    use HasFactory;

    protected $fillable = ['name', 'icon'];

    public function vehicle(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class);
    }
}
