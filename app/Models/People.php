<?php

namespace App\Models;

use App\Enums\{PersonType, Sexes};
use App\Observers\PeopleObserver;
use App\Traits\{HasAddress, HasAffiliate, HasPhone, HasPhoto};
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Class People
 *
 * @method HasMany employee()
 * @method BelongsTo user()
 * @method HasMany client()
 * @method HasMany seller()
 * @method HasMany receivedInstallments()
 * @method HasMany vehiclesAsBuyer()
 * @method HasMany vehiclesAsSupplier()
 * @method BelongsTo tenant()
 *
 * @property \App\Models\Employee $employee
 * @property \App\Models\User $user
 * @property \App\Models\Sale $client
 * @property \App\Models\Sale $seller
 * @property \App\Models\PaymentInstallment $receivedInstallments
 * @property \App\Models\Vehicle $vehiclesAsBuyer
 * @property \App\Models\Vehicle $vehiclesAsSupplier
 * @property \App\Models\Tenant $tenant
 * @property string $id
 * @property ?string $tenant_id
 * @property string $user_id
 * @property string $name
 * @property ?string $sex
 * @property ?string $email
 * @property ?string $rg
 * @property string $person_type
 * @property string $person_id
 * @property ?string $birthday
 * @property ?string $father
 * @property ?string $mother
 * @property ?string $marital_status
 * @property ?string $spouse
 * @property bool $client
 * @property bool $supplier
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
#[ObservedBy(PeopleObserver::class)]
class People extends BaseModel
{
    use HasFactory;
    use HasPhone;
    use HasPhoto;
    use HasAddress;
    use HasUlids;
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
