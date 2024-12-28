<?php

namespace App\Jobs;

use App\Models\{Ability, Company, Settings, Tenant, User};
use Database\Seeders\{AbilitySeeder, DatabaseSeeder};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateTenant implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Tenant $tenant)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tenant->run(function () {
            $storage_path = storage_path('framework/cache');

            if (!mkdir($storage_path, 0777, true) && !is_dir($storage_path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $storage_path));
            }

            //create .gitignore file
            $gitignore_path = $storage_path . '/.gitignore';

            if (!file_exists($gitignore_path)) {
                file_put_contents($gitignore_path, "*\n!data/\n!.gitignore");
            }

            //todo: create seeder for initial tenant data
            (new DatabaseSeeder())->run();

            /**
            $user = User::create(['name' => $this->tenant->name, 'email' => $this->tenant->email, 'password' => $this->tenant->password]);

            (new AbilitySeeder())->run();

            Company::query()->create(['name' => $this->tenant->name]);

            Settings::query()->create(['user_id' => $user->id]);

            $role = $user->roles()->create([
                'name'      => 'admin',
                'hierarchy' => 1,
            ]);

            $role->abilities()->sync(Ability::query()->pluck('id')->toArray());

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

            $brandIds = [];

            foreach ($brands as $brand) {
                $b                = \App\Models\Brand::query()->create(['name' => $brand]);
                $brandIds[$brand] = $b->id;
            }

            $types = [
                'Carro',
                'Motocicleta',
            ];

            $typeIds = [];

            foreach ($types as $type) {
                $t              = \App\Models\VehicleType::query()->create(['name' => $type]);
                $typeIds[$type] = $t->id;
            }

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
                $m = \App\Models\VehicleModel::query()->create([
                    'name'            => $model['name'],
                    'brand_id'        => $brandIds[$model['brand']], // Usa o ID gerado
                    'vehicle_type_id' => $typeIds[$model['type']], // Usa o ID gerado
                ]);
            }*/
        });
    }
}
