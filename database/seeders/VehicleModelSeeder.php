<?php

namespace Database\Seeders;

use App\Models\{Brand, VehicleModel, VehicleType};
use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands       = Brand::query()->pluck('id', 'name');
        $vehicleTypes = VehicleType::query()->pluck('id', 'name');

        $models = [
            ['name' => 'Strada', 'brand' => 'Fiat', 'type' => 'Carro'],
            ['name' => 'Uno', 'brand' => 'Fiat', 'type' => 'Carro'],
            ['name' => 'Vivace', 'brand' => 'Fiat', 'type' => 'Carro'],
            ['name' => 'Argo', 'brand' => 'Fiat', 'type' => 'Carro'],
            ['name' => 'Toro', 'brand' => 'Fiat', 'type' => 'Carro'],
            ['name' => 'Cronos', 'brand' => 'Fiat', 'type' => 'Carro'],
            ['name' => 'Mobi', 'brand' => 'Fiat', 'type' => 'Carro'],
            ['name' => 'Onix', 'brand' => 'Chevrolet', 'type' => 'Carro'],
            ['name' => 'Corsa', 'brand' => 'Chevrolet', 'type' => 'Carro'],
            ['name' => 'Onix Plus', 'brand' => 'Chevrolet', 'type' => 'Carro'],
            ['name' => 'Montana', 'brand' => 'Chevrolet', 'type' => 'Carro'],
            ['name' => 'S10', 'brand' => 'Chevrolet', 'type' => 'Carro'],
            ['name' => 'HB20', 'brand' => 'Hyundai', 'type' => 'Carro'],
            ['name' => 'HB20S', 'brand' => 'Hyundai', 'type' => 'Carro'],
            ['name' => 'Polo', 'brand' => 'Volkswagen', 'type' => 'Carro'],
            ['name' => 'Gol', 'brand' => 'Volkswagen', 'type' => 'Carro'],
            ['name' => 'Virtus', 'brand' => 'Volkswagen', 'type' => 'Carro'],
            ['name' => 'Saveiro', 'brand' => 'Volkswagen', 'type' => 'Carro'],
            ['name' => 'Renegade', 'brand' => 'Jeep', 'type' => 'Carro'],
            ['name' => 'City', 'brand' => 'Honda', 'type' => 'Carro'],
            ['name' => 'HR-V', 'brand' => 'Honda', 'type' => 'Carro'],
            ['name' => 'Oroch', 'brand' => 'Renault', 'type' => 'Carro'],
            ['name' => 'Kwid', 'brand' => 'Renault', 'type' => 'Carro'],
            ['name' => 'Duster', 'brand' => 'Renault', 'type' => 'Carro'],
            ['name' => 'Corolla Cross', 'brand' => 'Toyota', 'type' => 'Carro'],
            ['name' => 'Corolla', 'brand' => 'Toyota', 'type' => 'Carro'],
            ['name' => 'Hilux', 'brand' => 'Toyota', 'type' => 'Carro'],
            ['name' => 'Yaris', 'brand' => 'Toyota', 'type' => 'Carro'],
            ['name' => 'Hilux SW4', 'brand' => 'Toyota', 'type' => 'Carro'],
            ['name' => 'Yaris Sedan', 'brand' => 'Toyota', 'type' => 'Carro'],
            ['name' => 'Kicks', 'brand' => 'Nissan', 'type' => 'Carro'],
            ['name' => 'Ranger', 'brand' => 'Ford', 'type' => 'Carro'],
            ['name' => 'Bros', 'brand' => 'Honda', 'type' => 'Motocicleta'],
            ['name' => 'Titan', 'brand' => 'Honda', 'type' => 'Motocicleta'],
            ['name' => 'Crosser', 'brand' => 'Yamaha', 'type' => 'Motocicleta'],
            ['name' => 'Lander', 'brand' => 'Yamaha', 'type' => 'Motocicleta'],
            ['name' => 'Celta', 'brand' => 'Chevrolet', 'type' => 'Carro'],
            ['name' => 'Twister', 'brand' => 'Honda', 'type' => 'Motocicleta'],
            ['name' => 'XRE 190', 'brand' => 'Honda', 'type' => 'Motocicleta'],

        ];

        foreach ($models as $model) {
            VehicleModel::create([
                'name'            => $model['name'],
                'brand_id'        => $brands[$model['brand']] ?? null,
                'vehicle_type_id' => $vehicleTypes[$model['type']] ?? null,
            ]);
        }

    }
}
