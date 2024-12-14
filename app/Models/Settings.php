<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

/**
 * Class Settings
 *
 * @property int $id
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string|null $tertiary_color
 * @property string|null $quaternary_color
 * @property string|null $quinary_color
 * @property string|null $senary_color
 * @property bool $navigation_mode
 * @property string|null $font
 *
 */

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'quaternary_color',
        'quinary_color',
        'senary_color',
        'font',
        'navigation_mode',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'navigation_mode' => 'bool',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
