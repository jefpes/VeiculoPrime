<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};

/**
 * Class PaymentInstallments
 *
 * @property \App\Models\User $user
 * @property \App\Models\Sale $sale
 *
 * @method \App\Models\User user()
 * @method \App\Models\Sale sale()
 *
 * @property int $id
 * @property int $user_id
 * @property int $sale_id
 * @property \Illuminate\Support\Carbon $due_date
 * @property float $value
 * @property string $status
 * @property \Illuminate\Support\Carbon $payment_date
 * @property float $payment_value
 * @property string $payment_method
 * @property float $discount
 * @property float $surcharge
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class PaymentInstallments extends Model
{
    use HasFactory;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sale(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    // Método para verificar se todas as parcelas foram pagas
    public function scopeAllPaid(Builder $query, int $saleId): bool
    {
        return $query->where('sale_id', $saleId)->where('status', '!=', 'PAGO')->doesntExist();
    }

    // Método para verificar se alguma parcela foi paga
    public function scopeAnyPaid(Builder $query, int $saleId): bool
    {
        return $query->where('sale_id', $saleId)->where('status', 'PAGO')->exists();
    }
}
