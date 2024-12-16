<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

/**
 * Class Client
 * @property \App\Models\People $address
 *
 * @method \App\Models\People people()
 *
 * @property int $id
 * @property int $people_id
 * @property bool $active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Client extends Model
{
    use HasUlids;

    protected $fillable = [
        'people_id',
        'active',
    ];

    public function people(): BelongsTo
    {
        return $this->belongsTo(People::class);
    }
}
