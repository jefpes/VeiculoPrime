<?php

namespace App\Models;

use App\Traits\HasPhone;
use App\Traits\{HasAddress, HasPhoto};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasOne};

/**
 * Class Employee
 *
 * @property \App\Models\EmployeeAddress $address
 * @property \App\Models\Company $company
 * @property \App\Models\EmployeePhoto $photos
 *
 * @method \App\Models\EmployeeAddress address()
 * @method \App\Models\EmployeePhoto photos()
 * @method \App\Models\Company company()
 *
 * @property int $id
 * @property string $name
 * @property string $gender
 * @property string $email
 * @property string $phone_one
 * @property string|null $phone_two
 * @property float $salary
 * @property string $rg
 * @property string $cpf
 * @property \Illuminate\Support\Carbon $birth_date
 * @property string|null $father
 * @property string $mother
 * @property string $marital_status
 * @property string|null $spouse
 * @property \Illuminate\Support\Carbon $admission_date
 * @property \Illuminate\Support\Carbon|null $resignation_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Employee extends BaseModel
{
    use HasFactory;
    use HasAddress;
    use HasPhone;
    use HasPhoto;

    protected $fillable = [
        'tenant_id',
        'name',
        'gender',
        'email',
        'salary',
        'rg',
        'cpf',
        'birth_date',
        'father',
        'mother',
        'marital_status',
        'spouse',
        'admission_date',
        'resignation_date',
    ];

    public function ceo(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
