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
 * @method photoable()
 *
 * @property \Illuminate\Database\Eloquent\Model $photoable
 *
 * @property int $id
 * @property string $path
 * @property bool $is_main
 * @property bool $is_public
 *
 *
 */
class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'is_main',
        'is_public',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_main'   => 'boolean',
            'is_public' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($photo) {
            if (Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }
        });

        static::saving(function ($photo) {
            if ($photo->isDirty('path')) {
                $photo->handlePhotoSaving();
            }
        });
    }

    public function photoable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function handlePhotoSaving(): void
    {
        if (!$this->path) {
            return;
        }

        $directory = $this->photoable->getPhotoDirectory(); //@phpstan-ignore-line

        $newFileName = sprintf(
            '%s_%s.%s',
            Str::slug($this->photoable->getPhotoNamePrefix()), //@phpstan-ignore-line
            (string) Str::uuid(),
            pathinfo($this->path, PATHINFO_EXTENSION)
        );

        $newFilePath = $directory . '/' . $newFileName;

        if (Storage::disk('public')->exists($this->path)) {
            Storage::disk('public')->move($this->path, $newFilePath);
            $this->path = $newFilePath;
        }
    }
}
