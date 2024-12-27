<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method \App\Models\Role roles()
 * @property \App\Models\Role $roles
 * @property string $id
 * @property string $name
 */
class Ability extends Model
{
    use HasFactory;
    use HasUlids;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
