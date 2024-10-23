<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Client $clients
 * @property-read Employee $employees
 * @property-read Supplier $suppliers
 * @property-read int $id
 * @property-read string $name
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
