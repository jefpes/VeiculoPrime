<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Carro',
            'Motocicleta',
        ];

        foreach ($types as $type) {
            \App\Models\VehicleType::create(['name' => $type]);
        }
    }
}
