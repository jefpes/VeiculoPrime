<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class BasePhoto
 *
 * @property string $path
 */
abstract class BasePhoto extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($photo) {
            Storage::disk('public')->delete($photo->path);
        });

        static::saving(function ($photo) {
            if ($photo->isDirty('path')) {
                $photo->handlePhotoSaving();
            }
        });
    }

    abstract protected function getPhotoDirectory(): string;

    abstract protected function getPhotoNamePrefix(): string;

    protected function handlePhotoSaving(): void
    {
        $oldPhoto = $this->getOriginal('path');

        $newFileName = sprintf(
            '%s_%s.%s',
            $this->getPhotoNamePrefix(),
            (string) Str::uuid(),
            pathinfo($this->path, PATHINFO_EXTENSION)
        );

        $newFilePath = $this->getPhotoDirectory() . '/' . $newFileName;

        Storage::disk('public')->move($this->path, $newFilePath);

        $this->path = $newFilePath;

        if ($oldPhoto && $oldPhoto !== $this->path) {
            Storage::disk('public')->delete($oldPhoto);
        }
    }
}
