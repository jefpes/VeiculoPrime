<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Class EmployeePhoto
 *
 * @property \App\Models\Supplier $supplier
 *
 * @method \App\Models\Supplier supplier()
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $path
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SupplierPhoto extends BasePhoto
{
    protected function getPhotoDirectory(): string
    {
        return 'supplier_photos';
    }

    protected function getPhotoNamePrefix(): string
    {
        return Str::slug($this->supplier->name);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
