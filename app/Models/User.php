<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne};
use Illuminate\Database\Eloquent\{Builder, SoftDeletes};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property \App\Models\Role $roles
 * @property \App\Models\Employee $employee
 * @property Collection $abilities
 *
 * @method \App\Models\Role roles()
 * @method \App\Models\Employee employee()
 * @method Collection abilities()
 *
 * @property int $id
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

    public function hierarchy(int $id): bool
    {
        // $h_user_loged = $this->roles()->pluck('hierarchy')->max();
        // $h_user_param = (User::withTrashed()->find($id)->roles()->pluck('hierarchy')->max() ?? $h_user_loged + 1);
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

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(Settings::class);
    }

    public function abilities(): Collection
    {
        return $this->roles()->with('abilities')->get()->pluck('abilities.*.name')->flatten();
    }

    public function hasAbility(string $ability): bool
    {
        return $this->abilities()->contains($ability);
    }
}
