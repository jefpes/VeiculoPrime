<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Affiliate
 *
 * @package App\Models
 * @method MorphTo affiliatable()
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
        'affiliatable_id',
        'affiliatable_type',
    ];

    public function affiliatable(): MorphTo
    {
        return $this->morphTo();
    }
}
