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
 * @property \App\Models\VehiclePhoto $photos
 * @property \App\Models\VehicleDocPhoto $docPhotos
 * @property \App\Models\Sale $sale
 * @property \App\Models\VehicleExpense $expenses
 * @property \App\Models\Supplier $supplier
 * @property \App\Models\Employee $employee
 *
 * @method \App\Models\VehicleModel model()
 * @method \App\Models\VehiclePhoto photos()
 * @method \App\Models\VehicleDocPhoto docPhotos()
 * @method \App\Models\Sale sale()
 * @method \App\Models\VehicleExpense expenses()
 * @method \App\Models\Supplier supplier()
 * @method \App\Models\Employee employee()
 *
 * @property int $id
 * @property int $vehicle_model_id
 * @property int $supplier_id
 * @property int $employee_id
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
        'employee_id',
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

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
