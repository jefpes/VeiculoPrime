<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

class Supplier extends Model
{
    use HasFactory;

    public function photos(): HasMany
    {
        return $this->hasMany(SupplierPhoto::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(SupplierAddress::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
