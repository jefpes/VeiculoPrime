<?php

namespace App\Models;

use App\Traits\TenantScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaseModel extends Model
{
    use TenantScopeTrait;

    /**
     * @var array<string>
     */
    protected $with = ['tenant'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
