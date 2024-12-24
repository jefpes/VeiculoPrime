<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        $data = [
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

        foreach ($data as $d) {
            if ($tenant_id) {
                Brand::create(['name' => $d, 'tenant_id' => $tenant_id]);
            } else {
                Brand::create(['name' => $d]);
            }
        }
    }
}
