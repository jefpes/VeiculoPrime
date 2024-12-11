<?php

namespace App\Models;

use App\Traits\{HasAddress, HasPhoto};
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Client
 * @property \App\Models\ClientAddress $address
 * @property \App\Models\ClientPhoto $photos
 *
 * @method \App\Models\ClientAddress address()
 * @method \App\Models\ClientPhoto photos()
 *
 * @property int $id
 * @property string $name
 * @property string $gender
 * @property string $rg
 * @property string $client_type
 * @property string $client_id
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
class Client extends BaseModel
{
    use HasFactory;
    use HasAddress;
    use HasPhoto;
}
