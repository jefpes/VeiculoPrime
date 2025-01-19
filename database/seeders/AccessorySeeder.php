<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Airbag',
            'Ar condicionado',
            'Alarme',
            'Bancos de couro',
            'Blindado',
            'Câmera de ré',
            'Com kit GNV',
            'Computador de bordo',
            'Conexão USB',
            'Controle automático de velocidade',
            'Interface Bluetooth',
            'Navegador GPS',
            'Rodas de liga leve',
            'Sensor de ré',
            'Som',
            'Teto solar',
            'Tração 4x4',
            'Trava elétrica',
            'Vidro elétrico',
            'Volante multifuncional',
        ];

        foreach ($data as $d) {
            Accessory::create(['name' => $d]);
        }
    }
}
