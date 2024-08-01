<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

class Client extends Model
{
    use HasFactory;

    public function photos(): HasMany
    {
        return $this->hasMany(ClientPhoto::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(ClientAddress::class);
    }
}
