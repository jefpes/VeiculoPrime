<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \App\Models\Client $clients
 * @property \App\Models\Employee $employees
 * @property \App\Models\Supplier $suppliers
 * @method \App\Models\Client clients()
 * @method \App\Models\Employee employees()
 * @method \App\Models\Supplier suppliers()
 * @property int $id
 * @property string $name
 */
class City extends Model
{
    use HasFactory;

    public function clients(): HasMany
    {
        return $this->hasMany(ClientAddress::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(EmployeeAddress::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(SupplierAddress::class);
    }

}
