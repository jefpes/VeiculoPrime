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
            $photo    = "supplier_$i.webp";
            $supplier = Supplier::factory()->withAddress()->create();

            $folder = "supplier_photos";

            $supplier->photos()->create([
                'path' => "$folder/$photo",
            ]);
        }
    }
}
