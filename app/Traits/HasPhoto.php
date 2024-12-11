<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Relations\{MorphMany};

trait HasPhoto
{
    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function mainPhoto(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->morphOne(Photo::class, 'photoable')->where('is_main', true);
    }

    public function publicPhotos(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->morphMany(Photo::class, 'photoable')->where('is_public', true);
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
}
