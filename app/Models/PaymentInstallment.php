<?php

namespace App\Models;

use App\Traits\HasStore;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Builder};
use Illuminate\Support\Carbon;

/**
 * Class PaymentInstallments
 *
 * @property \App\Models\People $receiver
 * @property \App\Models\Sale $sale
 * @property \App\Models\Store $store
 * @property \App\Models\Tenant $tenant
 *
 * @method BelongsTo receiver()
 * @method BelongsTo sale()
 * @method BelongsTo store()
 * @method BelongsTo tenant()
 *
 * @property string $id
 * @property string $tenant_id
 * @property string $store_id
 * @property string $received_by
 * @property string $sale_id
 * @property Carbon $due_date
 * @property float $value
 * @property string $status
 * @property ?Carbon $payment_date
 * @property string $payment_method
 * @property ?float $discount
 * @property ?float $interest
 * @property ?float $interest_rate
 * @property ?float $late_fee
 * @property ?float $payment_value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PaymentInstallment extends BaseModel
{
    use HasUlids;
    use HasFactory;
    use HasStore;

    protected $fillable = [
        'tenant_id',
        'store_id',
        'received_by',
        'sale_id',
        'due_date',
        'value',
        'status',
        'payment_date',
        'late_fee',
        'interest_rate',
        'interest',
        'payment_value',
        'payment_method',
        'discount',
    ];

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(People::class, 'received_by');
    }

    public function sale(): BelongsTo
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
