<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Extra
 * @package App\Models
 *
 * @method BelongsToMany vehicle()
 *
 * @property \App\Models\Vehicle $vehicle
 * @property string $id
 * @property string $name
 */
class Extra extends Model
{
    use HasUlids;
    use HasFactory;

    protected $fillable = ['name'];

    public function vehicle(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class);
    }
}
