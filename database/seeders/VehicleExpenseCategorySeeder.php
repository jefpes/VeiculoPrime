<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VehicleExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        $categories = [
            'Combustível',
            'Manutenção',
            'Limpeza',
            'Outros',
        ];

        foreach ($categories as $category) {
            \App\Models\VehicleExpenseCategory::factory()->create([
                'tenant_id' => $tenant_id,
                'name'      => $category,
            ]);
        }
    }
}
