<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasOne};

/**
 * Class Company
 *
 * @property \App\Models\Employee $ceo
 * @method \App\Models\Employee ceo()
 * @property \App\Models\City $city
 * @method \App\Models\City city()
 *
 * @property int $id
 * @property int|null $employee_id
 * @property string|null $name
 * @property string|null $cnpj
 * @property string|null $opened_in
 * @property string $zip_code
 * @property string $street
 * @property string $number
 * @property string $neighborhood
 * @property int $city_id
 * @property string $state
 * @property string|null $complement
 * @property string|null $about
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $logo
 * @property string|null $favicon
 * @property string|null $x
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $linkedin
 * @property string|null $youtube
 * @property string|null $whatsapp
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    public function ceo(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function city(): HasOne
    {
        return $this->hasOne(City::class);
    }
}
