<?php

namespace App\Models;

use App\Enums\PersonType;
use App\Traits\{HasAddress, HasAffiliate, HasPhone, HasPhoto};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

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
        'gender',
        'email',
        'salary',
        'rg',
        'person_type',
        'person_id',
        'birth_date',
        'father',
        'mother',
        'marital_status',
        'spouse',
    ];

    public function casts(): array
    {
        return [
            'person_type' => PersonType::class,
        ];
    }

    public function ceo(): HasOne
    {
        return $this->hasOne(Company::class, 'employee_id');
    }

    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class, 'employee_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'people_id');
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class, 'people_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'people_id');
    }
}
