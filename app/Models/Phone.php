<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Phone
 *
 * @method MorphTo phonable()
 *
 * @property string $id
 * @property string $type
 * @property string $ddi
 * @property string $ddd
 * @property string $number
 * @property string $phonable_id
 * @property string $phonable_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Phone extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'type',
        'ddi',
        'ddd',
        'number',
        'phonable_id',
        'phonable_type',
    ];

    public function phonable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFullNumberAttribute(): string
    {
        if ($this->type) {
            return "+{$this->ddi} ({$this->ddd}) {$this->number} ({$this->type})";
        }

        return "+{$this->ddi} ({$this->ddd}) {$this->number}";
    }

    public function gerarLinkWhatsApp(): string
    {
        // Formata o número
        $numero = preg_replace('/[^\d]/', '', ($this->ddi . $this->ddd . $this->number));

        // Monta o link do WhatsApp
        return "https://wa.me/$numero";
    }

}
