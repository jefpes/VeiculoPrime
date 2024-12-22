<?php

namespace App\Models;

use App\Enums\{PersonType, Sexes};
use App\Traits\{HasAddress, HasAffiliate, HasPhone, HasPhoto};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

/**
 * @property \App\Models\Company $ceo
 * @property \App\Models\Employee $employee
 * @property \App\Models\User $user
 * @property \App\Models\Sale $sales
 * @property \App\Models\Vehicle $vehiclesAsBuyer
 * @property \App\Models\Vehicle $vehiclesAsSupplier
 * @property string $id
 * @property string $tenant_id
 * @property string $user_id
 * @property string $name
 * @property string $sex
 * @property string $email
 * @property string $rg
 * @property string $person_type
 * @property string $person_id
 * @property string $birthday
 * @property string $father
 * @property string $mother
 * @property string $marital_status
 * @property string $spouse
 * @property bool $client
 * @property bool $supplier
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class People extends BaseModel
{
    use HasFactory;
    use HasPhone;
    use HasPhoto;
    use HasAddress;
    use HasAffiliate;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'sex',
        'email',
        'salary',
        'rg',
        'person_type',
        'person_id',
        'birthday',
        'father',
        'mother',
        'marital_status',
        'spouse',
        'client',
        'supplier',
    ];

    public function casts(): array
    {
        return [
            'person_type' => PersonType::class,
            'sex'         => Sexes::class,
            'client'      => 'boolean',
            'supplier'    => 'boolean',
        ];
    }

    public function ceo(): HasOne
    {
        return $this->hasOne(Company::class, 'employee_id');
    }

    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class, 'people_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client(): HasMany
    {
        return $this->hasMany(Sale::class, 'client_id');
    }

    public function seller(): HasMany
    {
        return $this->hasMany(Sale::class, 'seller_id');
    }

    public function receivedInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class, 'received_by');
    }

    public function vehiclesAsBuyer(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'buyer_id');
    }

    public function vehiclesAsSupplier(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'supplier_id');
    }
}
