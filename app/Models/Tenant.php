<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, HasOne};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

/**
 * Class Tenant
 *
 * @property string $id
 * @property string $name
 * @property string $domain
 * @property float $monthly_fee
 * @property int $due_day
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
 * @property \App\Models\Settings $setting
 * @method HasMany setting()
 * @property string $getTenantUrl
 * @method string getTenantUrl()
 */
class Tenant extends Model
{
    use SoftDeletes;
    use HasUlids;

    protected $fillable = [
        'name',
        'domain',
        'monthly_fee',
        'due_day',
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
            'active' => 'boolean',
        ];
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
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

    public function setting(): HasOne
    {
        return $this->hasOne(Settings::class);
    }

    public function getTenantUrl(): string
    {
        if (config('app.env') === 'local') {
            return 'http://' . $this->domain . '.' . $_SERVER["HTTP_HOST"];
        }

        return 'https://' . $this->domain . '.' . $_SERVER["HTTP_HOST"];
    }
}
