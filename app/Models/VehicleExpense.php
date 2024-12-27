<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
class VehicleExpense extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
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
