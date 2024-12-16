<?php

namespace App\Models;

use App\Traits\{HasAddress, HasAffiliate, HasPhone, HasPhoto};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;
    use HasUlids;
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
}
