<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class Photo
 *
 * @property-read Model $photoable  Polymorphic relation to the owning model.
 * @property string $path
 * @property bool $is_main
 * @property string $photoable_type
 * @property int $photoable_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Photo extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Photo $photo) {
            Storage::disk('public')->delete($photo->path);
        });

        static::saving(function (Photo $photo) {
            if ($photo->isDirty('path')) {
                $photo->handlePhotoSaving();
            }
        });
    }

    /**
     * Define the polymorphic relationship.
     *
     * @return MorphTo
     */
    public function photoable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Handle the logic for saving a photo.
     */
    protected function handlePhotoSaving(): void
    {
        $oldPhoto  = $this->getOriginal('path');
        $directory = $this->photoable->getPhotoDirectory(); //@phpstan-ignore-line

        // Generate new file name
        $newFileName = sprintf(
            '%s_%s.%s',
            Str::slug($this->photoable->getPhotoNamePrefix()), //@phpstan-ignore-line
            (string) Str::uuid(),
            pathinfo($this->path, PATHINFO_EXTENSION)
        );

        // Define the new path
        $newFilePath = $directory . '/' . $newFileName;

        // Move the file to the new location
        Storage::disk('public')->move($this->path, $newFilePath);

        // Update the path
        $this->path = $newFilePath;

        // Delete the old file, if it exists and is different
        if ($oldPhoto && $oldPhoto !== $this->path) {
            Storage::disk('public')->delete($oldPhoto);
        }
    }
}
