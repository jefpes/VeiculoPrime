<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Fiat',
            'Chevrolet',
            'Hyundai',
            'Volkswagen',
            'Jeep',
            'Honda',
            'Renault',
            'Toyota',
            'Nissan',
            'Ford',
            'Yamaha',
        ];

        foreach ($brands as $brand) {
            \App\Models\Brand::create(['name' => $brand]);
        }
    }
}
