<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Affiliate;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasAffiliate
 *
 * @package App\Traits
 * @method MorphMany affiliates()
 *
 * @property \App\Models\Affiliate $affiliates
 */
trait HasAffiliate
{
    public function affiliates(): MorphMany
    {
        return $this->morphMany(Affiliate::class, 'affiliatable');
    }
}
