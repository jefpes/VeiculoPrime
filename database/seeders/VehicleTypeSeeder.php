<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        $data = [
            'Carro',
            'Motocicleta',
        ];

        foreach ($data as $d) {
            VehicleType::create(['name' => $d, 'tenant_id' => $tenant_id]);
        }
    }
}
