<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SupplierAddress
 *
 * @property \App\Models\Supplier $supplier
 * @property \App\Models\City $city
 *
 * @method \App\Models\Supplier supplier()
 * @method \App\Models\City city()
 *
 * @property int $id
 * @property int $supplier_id
 * @property int $city_id
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

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
