<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Affiliate
 * @property string $id
 * @property string $type
 * @property string $name
 * @property string $phone
 * @property string $affiliatable_id
 * @property string $affiliatable_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Affiliate extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'type',
        'name',
        'phone',
    ];

    public function affiliatable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
