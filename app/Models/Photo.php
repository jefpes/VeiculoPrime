<?php

namespace App\Models;

use App\Observers\PhotoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Photo
 *
 * @method MorphTo photoable()
 *
 * @property Model $photoable
 *
 * @property string $id
 * @property string $path
 * @property bool $main
 * @property bool $public
 */
#[ObservedBy(PhotoObserver::class)]
class Photo extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'path',
        'main',
        'public',
        'photoable_id',
        'photoable_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'main'   => 'boolean',
            'public' => 'boolean',
        ];
    }

    public function photoable(): MorphTo
    {
        return $this->morphTo();
    }
}
