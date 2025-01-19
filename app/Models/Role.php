<?php

namespace App\Models;

use App\Observers\RoleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\{Builder};

/**
 * Class Role
 *
 * @method BelongsToMany users()
 * @method BelongsToMany abilities()
 * @method BelongsTo tenant()
 *
 * @property \App\Models\User $users
 * @property \App\Models\Ability $abilities
 * @property \App\Models\Tenant $tenant
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property int $hierarchy
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[ObservedBy(RoleObserver::class)]
class Role extends BaseModel
{
    use HasUlids;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'hierarchy',
    ];

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
        return $q->where('hierarchy', '>=', $user->roles()->pluck('hierarchy')->max());
    }
}
