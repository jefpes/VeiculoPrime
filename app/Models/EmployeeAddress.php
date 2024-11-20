<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class EmployeeAddress
 *
 * @property \App\Models\Employee $employee
 *
 * @method \App\Models\Employee employee()
 *
 * @property int $id
 * @property int $employee_id
 * @property string $city
 * @property string $street
 * @property string $number
 * @property string $complement
 * @property string $district
 * @property string $zip_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EmployeeAddress extends Model
{
    use HasFactory;

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
