<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'domain',
        'monthly_fee',
        'due_day',
        'include_in_marketplace',
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

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
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

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
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
