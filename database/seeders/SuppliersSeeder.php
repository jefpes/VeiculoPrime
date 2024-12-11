<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $photo  = "supplier_$i.webp";
            $client = Supplier::factory()->withAddress()->create();

            $folder = "photos/supplier";

            // Desabilitar eventos para a criação da foto
            \App\Models\SupplierPhoto::withoutEvents(function () use ($client, $folder, $photo) {
                $client->photos()->create([
                    'path' => "$folder/$photo",
                ]);
            });
        }
    }
}
