<?php

namespace App\Models;

use App\Traits\HasStore;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VehicleExpense
 *
 * @method BelongsTo vehicle()
 * @method BelongsTo user()
 * @method BelongsTo store()
 * @method BelongsTo tenant()
 *
 * @property \App\Models\Vehicle $vehicle
 * @property \App\Models\User $user
 * @property \App\Models\Store $store
 * @property \App\Models\Tenant $tenant
 * @property string $id
 * @property string $tenant_id
 * @property string $store_id
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
    use HasUlids;
    use HasFactory;
    use HasStore;

    protected $fillable = [
        'tenant_id',
        'store_id',
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
