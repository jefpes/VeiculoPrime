<?php

namespace App\Models;

use App\Enums\{PersonType, Sexes};
use App\Traits\{HasAddress, HasAffiliate, HasPhone, HasPhoto};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

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
}
