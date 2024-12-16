<?php

namespace App\Models;

use App\Enums\PersonType;
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
        'gender',
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
        return $this->hasMany(Employee::class, 'people_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'people_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'people_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
