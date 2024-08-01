<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $models = [
            ['name' => 'Strada', 'brand_id' => 1, 'vehicle_type_id' => 1],
            ['name' => 'Uno', 'brand_id' => 1, 'vehicle_type_id' => 1],
            ['name' => 'Vivace', 'brand_id' => 1, 'vehicle_type_id' => 1],
            ['name' => 'Argo', 'brand_id' => 1, 'vehicle_type_id' => 1],
            ['name' => 'Toro', 'brand_id' => 1, 'vehicle_type_id' => 1],
            ['name' => 'Cronos', 'brand_id' => 1, 'vehicle_type_id' => 1],
            ['name' => 'Mobi', 'brand_id' => 1, 'vehicle_type_id' => 1],
            ['name' => 'Onix', 'brand_id' => 2, 'vehicle_type_id' => 1],
            ['name' => 'Corsa', 'brand_id' => 2, 'vehicle_type_id' => 1],
            ['name' => 'Onix Plus', 'brand_id' => 2, 'vehicle_type_id' => 1],
            ['name' => 'Montana', 'brand_id' => 2, 'vehicle_type_id' => 1],
            ['name' => 'S10', 'brand_id' => 2, 'vehicle_type_id' => 1],
            ['name' => 'HB20', 'brand_id' => 3, 'vehicle_type_id' => 1],
            ['name' => 'HB20S', 'brand_id' => 3, 'vehicle_type_id' => 1],
            ['name' => 'Polo', 'brand_id' => 4, 'vehicle_type_id' => 1],
            ['name' => 'Gol', 'brand_id' => 4, 'vehicle_type_id' => 1],
            ['name' => 'Virtus', 'brand_id' => 4, 'vehicle_type_id' => 1],
            ['name' => 'Saveiro', 'brand_id' => 4, 'vehicle_type_id' => 1],
            ['name' => 'Renegade', 'brand_id' => 5, 'vehicle_type_id' => 1],
            ['name' => 'City', 'brand_id' => 6, 'vehicle_type_id' => 1],
            ['name' => 'HR-V', 'brand_id' => 6, 'vehicle_type_id' => 1],
            ['name' => 'Oroch', 'brand_id' => 7, 'vehicle_type_id' => 1],
            ['name' => 'Kwid', 'brand_id' => 7, 'vehicle_type_id' => 1],
            ['name' => 'Duster', 'brand_id' => 7, 'vehicle_type_id' => 1],
            ['name' => 'Corolla Cross', 'brand_id' => 8, 'vehicle_type_id' => 1],
            ['name' => 'Corolla', 'brand_id' => 8, 'vehicle_type_id' => 1],
            ['name' => 'Hilux', 'brand_id' => 8, 'vehicle_type_id' => 1],
            ['name' => 'Yaris', 'brand_id' => 8, 'vehicle_type_id' => 1],
            ['name' => 'Hilux SW4', 'brand_id' => 8, 'vehicle_type_id' => 1],
            ['name' => 'Yaris Sedan', 'brand_id' => 8, 'vehicle_type_id' => 1],
            ['name' => 'Kicks', 'brand_id' => 9, 'vehicle_type_id' => 1],
            ['name' => 'Ranger', 'brand_id' => 10, 'vehicle_type_id' => 1],
            ['name' => 'Bros', 'brand_id' => 6, 'vehicle_type_id' => 2],
            ['name' => 'Titan', 'brand_id' => 6, 'vehicle_type_id' => 2],
            ['name' => 'Crosser', 'brand_id' => 11, 'vehicle_type_id' => 2],
            ['name' => 'Lander', 'brand_id' => 11, 'vehicle_type_id' => 2],
            ['name' => 'Celta', 'brand_id' => 2, 'vehicle_type_id' => 1],
            ['name' => 'Twister', 'brand_id' => 6, 'vehicle_type_id' => 2],
            ['name' => 'XRE 190', 'brand_id' => 6, 'vehicle_type_id' => 2],

        ];

        foreach ($models as $model) {
            \App\Models\VehicleModel::create($model);
        }
    }
}
