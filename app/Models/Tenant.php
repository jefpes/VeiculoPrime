<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

/**
 * Class Tenant
 *
 * @property string $code
 * @property string $name
 * @property string $domain
 * @property float $monthly_fee
 * @property int $due_day
 * @property bool $marketplace
 * @property bool $active
 *
 * @property \App\Models\User $user
 * @method BelongsToMany user()
 * @property \App\Models\Brand $brands
 * @method HasMany brands()
 * @property \App\Models\People $people
 * @method HasMany people()
 * @property \App\Models\PaymentInstallment $installments
 * @method HasMany installments()
 * @property \App\Models\Role $roles
 * @method HasMany roles()
 * @property \App\Models\Sale $sales
 * @method HasMany sales()
 * @property \App\Models\Vehicle $vehicles
 * @method HasMany vehicles()
 * @property \App\Models\Accessory $accessories
 * @method HasMany accessories()
 * @property \App\Models\Extra $extras
 * @method HasMany extras()
 * @property \App\Models\VehicleExpense $vehicleExpenses
 * @method HasMany vehicleExpenses()
 * @property \App\Models\VehicleModel $vehicleModels
 * @method HasMany vehicleModels()
 * @property \App\Models\VehicleType $vehicleTypes
 * @method HasMany vehicleTypes()
 */
class Tenant extends Model
{
    use SoftDeletes;
    use HasUlids;

    protected $fillable = [
        'code',
        'name',
        'domain',
        'monthly_fee',
        'due_day',
        'marketplace',
        'active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'include_in_marketplace' => 'boolean',
            'active'                 => 'boolean',
        ];
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
    public function people(): HasMany
    {
        return $this->hasMany(People::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(Accessory::class);
    }

    public function extras(): HasMany
    {
        return $this->hasMany(Extra::class);
    }

    public function vehicleExpenses(): HasMany
    {
        return $this->hasMany(VehicleExpense::class);
    }

    public function vehicleModels(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }

    public function vehicleTypes(): HasMany
    {
        return $this->hasMany(VehicleType::class);
    }
}
