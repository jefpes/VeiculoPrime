<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Phone;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasPhone
 *
 * @package App\Traits
 * @method MorphMany phones()
 *
 * @property \App\Models\Phone $phones
 */
trait HasPhone
{
    public function phones(): MorphMany
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }
}
