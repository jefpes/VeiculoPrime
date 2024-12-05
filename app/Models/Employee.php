<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

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

    public function address(): HasOne
    {
        return $this->hasOne(EmployeeAddress::class);
    }

    public function ceo(): HasOne
    {
        return $this->hasOne(Settings::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(EmployeePhoto::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
