<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SupplierAddress
 *
 * @property \App\Models\Supplier $supplier
 *
 * @method \App\Models\Supplier supplier()
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $city
 * @property string $address
 * @property string $neighborhood
 * @property string $zip_code
 * @property string $complement
 * @property string $number
 * @property string $reference
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SupplierAddress extends Model
{
    use HasFactory;

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
