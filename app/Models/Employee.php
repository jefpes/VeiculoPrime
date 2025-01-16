<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

/**
 * Class Employee
 *
 * @method BelongsTo people()
 *
 * @property \App\Models\People $people
 *
 * @property string $id
 * @property string $people_id
 * @property float $salary
 * @property \Illuminate\Support\Carbon $admission_date
 * @property \Illuminate\Support\Carbon|null $resignation_date
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Employee extends Model
{
    use HasUlids;

    protected $fillable = [
        'people_id',
        'salary',
        'admission_date',
        'resignation_date',
    ];

    public function people(): BelongsTo
    {
        return $this->belongsTo(People::class);
    }
}
