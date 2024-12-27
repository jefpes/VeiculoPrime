<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\{HasDatabase, HasDomains};
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;
    use HasDomains;

    protected $fillable = ['name', 'email', 'password', 'data'];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    /**
         * Get the attributes that should be cast.
         *
         * @return array<string>
         */
    public static function getCustomColumns(): array
    {
        return ['id', 'name', 'email', 'password'];
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getPasswordAttribute(string $value): string
    {
        return $this->attributes['password'] = bcrypt($value);
    }
}
