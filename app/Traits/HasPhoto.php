<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Photo;

/**
 * Trait HasPhoto
 *
 * @property \App\Models\Photo $photos
 * @property \App\Models\Photo $mainPhoto
 *
 * @method \App\Models\Photo photos()
 * @method \App\Models\Photo mainPhoto()
 *
 * @method string getPhotoDirectory()
 * @method string getPhotoNamePrefix()
 */
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

    abstract public function getPhotoDirectory(): string;
    abstract public function getPhotoNamePrefix(): string;
}
