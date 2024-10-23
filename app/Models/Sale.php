<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Class Sale
 *
 * @property \App\Models\User $user
 * @property \App\Models\Vehicle $vehicle
 * @property \App\Models\Client $client
 * @property \App\Models\PaymentInstallments $paymentInstallments
 *
 * @method \App\Models\User user()
 * @method \App\Models\Vehicle vehicle()
 * @method \App\Models\Client client()
 * @method \App\Models\PaymentInstallments paymentInstallments()
 *
 * @property int $id
 * @property int $user_id
 * @property int $vehicle_id
 * @property int $client_id
 * @property \Illuminate\Support\Carbon $date_sale
 * @property \Illuminate\Support\Carbon $date_payment
 * @property float $discount
 * @property float $surcharge
 * @property float $down_payment
 * @property int $number_installments
 * @property float $reimbursement
 * @property \Illuminate\Support\Carbon $date_cancel
 * @property float $total
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Sale extends Model
{
    use HasFactory;

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
        return $this->belongsTo(Client::class);
    }

    public function paymentInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallments::class);
    }
}
