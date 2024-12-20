<?php

namespace App\Models;

use App\Traits\HasPhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Class Vehicle
 *
 * @property \App\Models\VehicleModel $model
 * @property \App\Models\Photo $photos
 * @property \App\Models\Sale $sale
 * @property \App\Models\VehicleExpense $expenses
 * @property \App\Models\People $supplier
 * @property \App\Models\People $buyer
 *
 * @method \App\Models\VehicleModel model()
 * @method \App\Models\Photo photos()
 * @method \App\Models\Sale sale()
 * @method \App\Models\VehicleExpense expenses()
 * @method \App\Models\People supplier()
 * @method \App\Models\People buyer()
 *
 * @property int $id
 * @property int $vehicle_model_id
 * @property int $supplier_id
 * @property int $buyer_id
 * @property \Illuminate\Support\Carbon $purchase_date
 * @property float $fipe_price
 * @property float $purchase_price
 * @property float $sale_price
 * @property float $promotional_price
 * @property string $year_one
 * @property string $year_two
 * @property int $km
 * @property string $fuel
 * @property string $engine_power
 * @property string $steering
 * @property string $transmission
 * @property int $doors
 * @property int $seats
 * @property string $traction
 * @property string $color
 * @property string $plate
 * @property string $chassi
 * @property string $renavam
 * @property \Illuminate\Support\Carbon $sold_date
 * @property string $description
 * @property string $annotation
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Vehicle extends BaseModel
{
    use HasFactory;
    use HasPhoto;

    protected $fillable = [
        'tenant_id',
        'buyer_id',
        'vehicle_model_id',
        'supplier_id',
        'purchase_date',
        'fipe_price',
        'purchase_price',
        'sale_price',
        'promotional_price',
        'year_one',
        'year_two',
        'km',
        'fuel',
        'engine_power',
        'steering',
        'transmission',
        'doors',
        'seats',
        'traction',
        'color',
        'plate',
        'chassi',
        'renavam',
        'sold_date',
        'description',
        'annotation',
    ];

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id', 'id');
    }

    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the vehicle expenses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(VehicleExpense::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(People::class, 'buyer_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(People::class, 'supplier_id');
    }
}
