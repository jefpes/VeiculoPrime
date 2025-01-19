<?php

namespace App\Models;

use App\Observers\VehicleObserver;
use App\Traits\{HasPhoto, HasStore};
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasManyThrough};

/**
 * Class Vehicle
 *
 * @method BelongsTo model()
 * @method MorphMany photos()
 * @method HasMany sale()
 * @method HasManyThrough paymentInstallments()
 * @method HasMany expenses()
 * @method BelongsTo supplier()
 * @method BelongsTo buyer()
 * @method BelongsToMany accessories()
 * @method BelongsToMany extras()
 * @method BelongsTo store()
 * @method BelongsTo tenant()
 *
 * @property \App\Models\VehicleModel $model
 * @property \App\Models\Photo $photos
 * @property \App\Models\Sale $sale
 * @property \App\Models\PaymentInstallment $paymentInstallments
 * @property \App\Models\VehicleExpense $expenses
 * @property \App\Models\People $supplier
 * @property \App\Models\People $buyer
 * @property \App\Models\Accessory $accessories
 * @property \App\Models\Extra $extras
 * @property \App\Models\Store $store
 * @property \App\Models\Tenant $tenant
 *
 * @property string $id
 * @property string $tenant_id
 * @property string $vehicle_model_id
 * @property string $supplier_id
 * @property string $buyer_id
 * @property string $store_id
 * @property \Illuminate\Support\Carbon $purchase_date
 * @property float $fipe_price
 * @property float $purchase_price
 * @property float $sale_price
 * @property float|null $promotional_price
 * @property string $year_one
 * @property string $year_two
 * @property int $km
 * @property string $fuel
 * @property string $engine_power
 * @property string $steering
 * @property string $transmission
 * @property int $doors
 * @property int $seats
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
#[ObservedBy(VehicleObserver::class)]
class Vehicle extends BaseModel
{
    use HasUlids;
    use HasFactory;
    use HasPhoto;
    use HasStore;

    protected $fillable = [
        'tenant_id',
        'store_id',
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

    public function paymentInstallments(): HasManyThrough
    {
        return $this->hasManyThrough(PaymentInstallment::class, Sale::class);
    }

    public function accessories(): BelongsToMany
    {
        return $this->belongsToMany(Accessory::class);
    }

    public function extras(): BelongsToMany
    {
        return $this->belongsToMany(Extra::class);
    }

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

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
