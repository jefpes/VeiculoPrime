<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasAddress
 *
 * @package App\Traits
 * @method MorphMany addresses()
 *
 * @property \App\Models\Address $mainAddress
 */
trait HasAddress
{
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function getMainAddressAttribute(): ?Model
    {
        return $this->addresses()->first();
    }
}
