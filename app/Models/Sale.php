<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Class Sale
 *
 * @property \App\Models\Vehicle $vehicle
 * @property \App\Models\People $client
 * @property \App\Models\People $seller
 * @property \App\Models\PaymentInstallment $paymentInstallments
 *
 * @method \App\Models\Vehicle vehicle()
 * @method \App\Models\People client()
 * @method \App\Models\People seller()
 * @method \App\Models\PaymentInstallment paymentInstallments()
 *
 * @property string $id
 * @property string $store_id
 * @property string $vehicle_id
 * @property string $client_id
 * @property string $seller_id
 * @property \Illuminate\Support\Carbon $date_sale
 * @property \Illuminate\Support\Carbon $date_payment
 * @property string $status
 * @property float $discount
 * @property float|null $interest
 * @property float $interest_rate
 * @property float $down_payment
 * @property int $number_installments
 * @property float $reimbursement
 * @property \Illuminate\Support\Carbon $date_cancel
 * @property float $total
 * @property float|null $total_with_interest
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Sale extends Model
{
    use HasUlids;
    use HasFactory;
    use HasTenant;

    protected $fillable = [
        'store_id',
        'vehicle_id',
        'client_id',
        'seller_id',
        'payment_method',
        'status',
        'date_sale',
        'date_payment',
        'interest_rate',
        'discount',
        'interest',
        'down_payment',
        'number_installments',
        'reimbursement',
        'date_cancel',
        'total',
        'total_with_interest',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(People::class, 'client_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(People::class, 'seller_id');
    }

    public function paymentInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class, 'sale_id');
    }
}
