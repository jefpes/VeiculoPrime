<?php

namespace App\Models;

use App\Traits\TenantScopeTrait;
use Filament\Models\Contracts\{FilamentUser, HasAvatar, HasTenants};
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Class User
 *
 * @method BelongsToMany roles()
 * @method HasOne people()
 * @method Builder abilities()
 * @method HasMany sales()
 * @method BelongsToMany stores()
 * @method BelongsTo tenant()
 *
 * @property Role $roles
 * @property People $people
 * @property Collection $abilities
 * @property Collection $stores
 * @property Sale $sales
 * @property Tenant $tenant
 *
 * @property string $id
 * @property ?string $tenant_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property ?string $primary_color
 * @property ?string $secondary_color
 * @property ?string $tertiary_color
 * @property ?string $quaternary_color
 * @property ?string $quinary_color
 * @property ?string $senary_color
 * @property ?string $font
 * @property bool $navigation_mode
 * @property string|null $avatar_url
 */
class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasTenants, HasAvatar
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasUlids;
    use TenantScopeTrait;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'remember_token',
        'email_verified_at',
        'password',
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'quaternary_color',
        'quinary_color',
        'senary_color',
        'font',
        'navigation_mode',
        'avatar_url',
    ];

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
            'password'        => 'hashed',
            'navigation_mode' => 'bool',
        ];
    }

    /**
     * @var array<string>
     */
    protected $with = ['tenant'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
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

    public function abilities(): Builder
    {
        return Ability::query()->whereHas('roles', fn ($query) => $query->whereIn('id', $this->roles->pluck('id'))); //@phpstan-ignore-line
    }

    public function hasAbility(string $ability): bool
    {
        return $this->abilities()->where('name', $ability)->exists();
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->stores;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'master' && $this->tenant_id !== null) {
            return false;
        }

        return true;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->stores()->whereKey($tenant)->exists();
    }
}
