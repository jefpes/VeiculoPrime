<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ClientAddress
 *
 * @property \App\Models\Client $client
 *
 * @method \App\Models\Client client()
 * @property int $id
 * @property int $employee_id
 * @property string $city
 * @property string $street
 * @property string $number
 * @property string $complement
 * @property string $neighborhood
 * @property string $zip_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ClientAddress extends Model
{
    use HasFactory;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
