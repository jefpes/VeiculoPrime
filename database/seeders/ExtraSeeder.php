<?php

namespace Database\Seeders;

use App\Models\Extra;
use Illuminate\Database\Seeder;

class ExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
            Extra::create(['name' => $d]);
        }
    }
}
