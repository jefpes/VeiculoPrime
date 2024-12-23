<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Extra extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name'];

    public function vehicle(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class);
    }
}
