<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

/**
 * Class Supplier
 *
 * @property \App\Models\SupplierPhoto $photos
 * @property \App\Models\SupplierAddress $address
 * @property \App\Models\Vehicle $vehicles
 *
 * @method \App\Models\SupplierPhoto photos()
 * @method \App\Models\SupplierAddress address()
 * @method \App\Models\Vehicle vehicles()
 *
 * @property int $id
 * @property string $name
 * @property string $gender
 * @property string $rg
 * @property string $supplier_type
 * @property string $supplier_id
 * @property string $marital_status
 * @property string $spouse
 * @property string $phone_one
 * @property string|null $phone_two
 * @property \Illuminate\Support\Carbon $birth_date
 * @property string|null $father
 * @property string|null $father_phone
 * @property string $mother
 * @property string|null $mother_phone
 * @property string|null $affiliated_one
 * @property string|null $affiliated_one_phone
 * @property string|null $affiliated_two
 * @property string|null $affiliated_two_phone
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Supplier extends Model
{
    use HasFactory;

    public function photos(): HasMany
    {
        return $this->hasMany(SupplierPhoto::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(SupplierAddress::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
