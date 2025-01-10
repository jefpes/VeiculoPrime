<?php

namespace App\Observers;

use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoObserver
{
    public function retrieved(Photo $photo): void
    {
    }
    public function creating(Photo $photo): void
    {
    }
    public function created(Photo $photo): void
    {
    }
    public function updating(Photo $photo): void
    {
        if ($photo->isDirty('path')) {
            $this->deleteOldPhoto($photo);
        }
    }
    public function updated(Photo $photo): void
    {
    }
    public function saving(Photo $photo): void
    {
        if ($photo->isDirty('path')) {
            $this->handlePhotoSaving($photo);
        }
    }
    public function saved(Photo $photo): void
    {
    }
    public function deleting(Photo $photo): void
    {
        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }
    }
    public function deleted(Photo $photo): void
    {
    }
    public function trashed(Photo $photo): void
    {
    }
    public function forceDeleting(Photo $photo): void
    {
    }
    public function forceDeleted(Photo $photo): void
    {
    }
    public function restored(Photo $photo): void
    {
    }
    public function replicating(Photo $photo): void
    {
    }

    protected function handlePhotoSaving(Photo $photo): void
    {
        if (!$photo->path) {
            return;
        }

        $directory = $photo->photoable->getPhotoDirectory(); //@phpstan-ignore-line

        $newFileName = sprintf(
            '%s_%s.%s',
            Str::slug($photo->photoable->getPhotoNamePrefix()), //@phpstan-ignore-line
            (string) Str::uuid(),
            pathinfo($photo->path, PATHINFO_EXTENSION)
        );

        $newFilePath = $directory . '/' . $newFileName;

        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->move($photo->path, $newFilePath);
            $photo->path = $newFilePath;
        }
    }

    protected function deleteOldPhoto(Photo $photo): void
    {
        $originalPath = $photo->getOriginal('path');

        if ($originalPath && Storage::disk('public')->exists($originalPath)) {
            Storage::disk('public')->delete($originalPath);
        }
    }
}
