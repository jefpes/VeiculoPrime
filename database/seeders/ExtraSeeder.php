<?php

namespace Database\Seeders;

use App\Models\Extra;
use Illuminate\Database\Seeder;

class ExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        $data = [
            'Leilão',
            'Chave reserva',
            'Garantia de fábrica',
            'Manual',
            'Multas',
            'IPVA pago',
            'Revisões feitas na concessionária',
            'Único dono',
            'Veículo em financiamento',
            'Veículo quitado',
            'Apoio na documentação',
            'Entrega do veículo',
            'Garantia de motor',
            'Garantia de câmbio',
            'Pneus novos',
            'Tanque cheio',
        ];

        foreach ($data as $d) {
            if ($tenant_id) {
                Extra::create(['name' => $d, 'tenant_id' => $tenant_id]);
            } else {
                Extra::create(['name' => $d]);
            }
        }
    }
}
