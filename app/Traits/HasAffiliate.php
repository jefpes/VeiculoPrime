<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Affiliate;

trait HasAffiliate
{
    public function affiliates(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Affiliate::class, 'affiliatable');
    }
}
