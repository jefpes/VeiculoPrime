<?php

namespace App\Models;

use App\Traits\{HasPhone, HasPhoto};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

/**
 * Class Store
 *
 * @property \App\Models\User $users
 * @property \App\Models\Vehicle $vehicles
 * @property \App\Models\VehicleExpense $vehicleExpenses
 * @property \App\Models\Sale $sales
 * @property \App\Models\PaymentInstallment $paymentInstallments
 *
 * @method BelongsToMany users()
 * @method HasMany vehicles()
 * @method HasMany vehicleExpenses()
 * @method HasMany sales()
 * @method HasMany paymentInstallments()
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $zip_code
 * @property string $state
 * @property string $city
 * @property string $neighborhood
 * @property string $street
 * @property string $number
 * @property string $complement
 * @property bool $active
 * @property \Illuminate\Support\Carbon $created_at
 */
class Store extends Model
{
    use HasFactory;
    use HasUlids;
    use HasPhone;
    use HasPhoto;

    protected $fillable = [
        'name',
        'slug',
        'zip_code',
        'state',
        'city',
        'neighborhood',
        'street',
        'number',
        'complement',
        'active',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function vehicleExpenses(): HasMany
    {
        return $this->hasMany(VehicleExpense::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function paymentInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function getFullAddressAttribute(): string
    {
        if ($this->complement) {
            return "{$this->street}, {$this->number} - {$this->neighborhood}, {$this->city} - {$this->state} ({$this->complement})";
        }

        return "{$this->street}, {$this->number} - {$this->neighborhood}, {$this->city} - {$this->state}";
    }

    public function gerarLinkGoogleMaps(): string
    {
        // Monta o endereço completo
        $enderecoCompleto = trim("$this->street, $this->neighborhood, $this->city, $this->state, $this->zip_code");

        // Remove acentos e caracteres especiais
        $enderecoSemAcentos = $this->removerCaracteresEspeciais($enderecoCompleto);

        // Codifica o endereço para ser usado na URL
        $enderecoCodificado = urlencode($enderecoSemAcentos);

        // Monta a URL do Google Maps
        $urlGoogleMaps = "https://www.google.com/maps?q=$enderecoCodificado";

        return $urlGoogleMaps;
    }

    public function removerCaracteresEspeciais(string $texto): string
    {
        // Remove acentos
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);

        // Remove caracteres não permitidos (ex: cedilha que pode ser convertido em '?')
        $texto = preg_replace('/[^a-zA-Z0-9,.\s-]/', '', $texto);

        return $texto;
    }

}
