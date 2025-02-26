<?php

namespace App\Models;

use App\Traits\HasStore;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class VehicleExpenseCategory
 *
 * @method HasMany expenses()
 *
 * @property string $id
 * @property ?string $tenant_id
 * @property string $name
 * @property VehicleExpense $expenses
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class VehicleExpenseCategory extends BaseModel
{
    use HasFactory;
    use HasUlids;
    use HasStore;

    protected $fillable = ['name'];

    public function expenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VehicleExpense::class, 'vehicle_expense_category_id');
    }
}
