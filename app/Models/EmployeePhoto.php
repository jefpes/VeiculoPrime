<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Class EmployeePhoto
 *
 * @property \App\Models\Employee $employee
 *
 * @method \App\Models\Employee employee()
 *
 * @property int $id
 * @property int $employee_id
 * @property string $path
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EmployeePhoto extends BasePhoto
{
    protected function getPhotoDirectory(): string
    {
        return 'employee_photos';
    }

    protected function getPhotoNamePrefix(): string
    {
        return Str::slug($this->employee->name);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
