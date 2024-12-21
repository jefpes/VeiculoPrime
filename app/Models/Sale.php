<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Class Sale
 *
 * @property \App\Models\User $user
 * @property \App\Models\Vehicle $vehicle
 * @property \App\Models\Client $client
 * @property \App\Models\PaymentInstallment $paymentInstallments
 *
 * @method \App\Models\User user()
 * @method \App\Models\Vehicle vehicle()
 * @method \App\Models\People client()
 * @method \App\Models\PaymentInstallment paymentInstallments()
 *
 * @property string $id
 * @property string $user_id
 * @property string $vehicle_id
 * @property string $client_id
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
class Sale extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'vehicle_id',
        'client_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(People::class, 'client_id');
        // ->whereHas('client', fn ($query) => $query->where('is_active', true));
    }

    public function paymentInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class);
    }
}
