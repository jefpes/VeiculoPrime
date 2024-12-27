<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VehicleExpense
 * @property \App\Models\Vehicle $vehicle
 * @property \App\Models\User $user
 *
 * @method \App\Models\Vehicle vehicle()
 * @method \App\Models\User user()
 *
 * @property string $id
 * @property string $vehicle_id
 * @property string $user_id
 * @property \Illuminate\Support\Carbon $date
 * @property string $description
 * @property float $value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class VehicleExpense extends Model
{
    use HasUlids;
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'date',
        'description',
        'value',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
