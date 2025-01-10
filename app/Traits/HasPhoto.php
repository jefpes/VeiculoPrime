<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\{MorphMany, MorphOne};

/**
 * Trait HasPhoto
 *
 * @property \App\Models\Photo $photos
 * @property \App\Models\Photo $mainPhoto
 * @property \App\Models\Photo $publicPhotos
 *
 * @method MorphMany photos()
 * @method Builder mainPhoto()
 * @method Builder publicPhotos()
 *
 * @method Builder withPublicPhotos()
 * @method Builder withMainPhoto()
 *
 * @method string getPhotoDirectory()
 * @method string getPhotoNamePrefix()
 *
 * @property-read string $name
 * @property-read string $plate
 */
trait HasPhoto
{
    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function mainPhoto(): Builder | MorphOne
    {
        return $this->morphOne(Photo::class, 'photoable')->where('main', true);
    }

    public function publicPhotos(): Builder | MorphMany
    {
        return $this->morphMany(Photo::class, 'photoable')->where('public', true);
    }

    public function scopeWithPublicPhotos(Builder $query): Builder
    {
        return $query->with(['photos' => function ($query) {
            $query->where('public', true);
        }]);
    }

    public function scopeWithMainPhoto(Builder $query): Builder
    {
        return $query->with(['photos' => function ($query) {
            $query->where('public', true)->where('main', true);
        }]);
    }

    public function getPhotoDirectory(): string
    {
        return 'photos/' . strtolower(class_basename($this));
    }

    public function getPhotoNamePrefix(): string
    {
        $attributes = ['name', 'plate'];

        // Verifique cada atributo na ordem definida e use o primeiro que existir
        foreach ($attributes as $attribute) {
            if (isset($this->$attribute) && !empty($this->$attribute)) {
                return $this->$attribute;
            }
        }

        // Caso nenhum atributo seja encontrado, use o nome da classe como fallback
        return class_basename($this);
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $photo = $this->mainPhoto ?? $this->publicPhotos->first(); //@phpstan-ignore-line

                return $photo ? image_path($photo->path) : 'https://placehold.co/600x400';
            },
        );
    }
}
