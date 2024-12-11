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
            $photo = "supplier_$i.webp";
            $model = Supplier::factory()->withAddress()->withAffiliate()->withPhone()->create();

            $folder = "photos/supplier";

            // Desabilitar eventos para a criação da foto
            \App\Models\Photo::withoutEvents(function () use ($model, $folder, $photo) {
                $model->photos()->create([
                    'path' => "$folder/$photo",
                ]);
            });
        }
    }
}
