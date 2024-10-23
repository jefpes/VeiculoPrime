<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\{Builder, Model};

/**
 * Class Role
 *
 * @property \App\Models\User $users
 * @property \App\Models\Ability $abilities
 *
 * @method \App\Models\User users()
 * @method \App\Models\Ability abilities()
 *
 * @property int $id
 * @property string $name
 * @property int $hierarchy
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Role extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class);
    }

    public function scopeHierarchy(Builder $q, User $user): Builder
    {
        // return $q->where('hierarchy', '>=', $user->roles()->pluck('hierarchy')->max());
        return $q->where('hierarchy', '>=', $user->roles()->query()->pluck('hierarchy')->max());
    }
}
