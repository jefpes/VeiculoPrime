<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne};
use Illuminate\Database\Eloquent\{Builder, SoftDeletes};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property \App\Models\Role $roles
 * @property \App\Models\People $people
 * @property Collection $abilities
 *
 * @method \App\Models\Role roles()
 * @method \App\Models\People people()
 * @method Collection abilities()
 *
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $avatar_url
 */
class User extends Authenticatable implements MustVerifyEmail, HasAvatar
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasUlids;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'custom_fields',
        'avatar_url',
        'employee_id',
    ];

    /**
     * @var array<string>
     */
    protected $with = ['tenant'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }

    public function hierarchy(string $id): bool
    {
        $h_user_loged = collect($this->roles)->pluck('hierarchy')->max();
        $h_user_param = (collect(User::withTrashed()->find($id)->roles)->pluck('hierarchy')->max() ?? $h_user_loged + 1);

        return $h_user_loged <= $h_user_param;
    }

    public function scopeSearch(Builder $q, string $val): Builder
    {
        return $q->where('name', 'like', "%{$val}%");
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function people(): HasOne
    {
        return $this->hasOne(People::class, 'user_id');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(Settings::class);
    }

    public function abilities(): \Illuminate\Database\Eloquent\Builder
    {
        return Ability::query()->whereHas('roles', fn ($query) => $query->whereIn('id', $this->roles->pluck('id'))); //@phpstan-ignore-line
    }

    public function hasAbility(string $ability): bool
    {
        return $this->abilities()->where('name', $ability)->exists(); //@phpstan-ignore-line
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    // public function abilities(): Collection
    // {
    //     return $this->roles()->with('abilities')->get()->pluck('abilities.*.name')->flatten();
    // }

    // public function hasAbility(string $ability): bool
    // {
    //     return $this->abilities()->contains($ability);
    // }
}
