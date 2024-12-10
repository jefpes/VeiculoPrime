<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Photo;

trait HasPhoto
{
    public function photos(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function mainPhoto(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->morphOne(Photo::class, 'photoable')->where('is_main', true);
    }
}
