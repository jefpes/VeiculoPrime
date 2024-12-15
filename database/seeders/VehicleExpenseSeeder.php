<?php

namespace Database\Seeders;

use App\Models\{User, Vehicle, VehicleExpense};
use Illuminate\Database\Seeder;

class VehicleExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();
        $user     = User::first() ?? User::factory()->create();

        foreach ($vehicles as $vehicle) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                VehicleExpense::create([
                    'date'        => now()->subDays(rand(1, 180)),
                    'description' => 'Despesa de teste',
                    'value'       => rand(100, 100000) / 100,
                    'vehicle_id'  => $vehicle->id,
                    'user_id'     => $user->id,
                ]);
            }
        }
    }
}
