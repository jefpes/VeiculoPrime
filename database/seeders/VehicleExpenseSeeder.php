<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 15; $i++) {
            Vehicle::find(rand(1, 33))->expenses()->create([
                'date'        => '2024-' . rand(1, 6) . '-' . rand(1, 28),
                'description' => 'Despesa de teste',
                'value'       => rand(1, 1000),
                'user_id'     => 1,
            ]);
        }
    }
}
