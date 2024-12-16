<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 *
 * @property int $id
 * @property int $addressable_id
 * @property string $addressable_type
 * @property string $city
 * @property string $street
 * @property string $number
 * @property string $complement
 * @property string $neighborhood
 * @property string $state
 * @property string $zip_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Address extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'zip_code',
        'street',
        'number',
        'neighborhood',
        'city',
        'state',
        'complement',
    ];

    public function addressable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function getFullAddressAttribute(): string
    {
        if ($this->complement) {
            return "{$this->street}, {$this->number} - {$this->neighborhood}, {$this->city} - {$this->state} ({$this->complement})";
        }

        return "{$this->street}, {$this->number} - {$this->neighborhood}, {$this->city} - {$this->state}";
    }
}
