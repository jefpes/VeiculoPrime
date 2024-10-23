<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

/**
 * Class Employee
 * @property EmployeeAddress $address
 * @property Company $company
 * @property EmployeePhoto $photos
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

class Employee extends Model
{
    use HasFactory;

    public function address(): HasOne
    {
        return $this->hasOne(EmployeeAddress::class);
    }

    public function company(): HasMany
    {
        return $this->hasMany(Company::class);
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
