<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

class Client extends Model
{
    use HasFactory;

    public function getCombinedPhonesAttribute(): string
    {
        if ($this->phone_two === null) { //@phpstan-ignore-line
            return $this->phone_one; //@phpstan-ignore-line
        }

        return "{$this->phone_one} | {$this->phone_two}"; //@phpstan-ignore-line
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ClientPhoto::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(ClientAddress::class);
    }
}
