<?php

namespace App\Filament\Master\Resources\TenantResource\Pages;

use App\Filament\Master\Resources\TenantResource;
use App\Models\{Ability, Settings, Store, User};
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    public array $user = []; // @phpstan-ignore-line

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->user = [
            'name'              => $data['user_name'],
            'email'             => $data['user_email'],
            'email_verified_at' => now(),
            'password'          => bcrypt($data['user_password']),
            'remember_token'    => Str::random(10),
        ];

        return $data;
    }

    protected function afterCreate(): void
    {
        $setting = Settings::create(['name' => $this->record->name]); // @phpstan-ignore-line

        $setting->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line

        $store = Store::query()->create([
            'name' => $this->record->name, // @phpstan-ignore-line
            'slug' => Str::slug($this->record->domain), // @phpstan-ignore-line
        ]);

        $store->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line

        $user = User::create($this->user); // @phpstan-ignore-line

        $user->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line

        $store->users()->attach($user->id);

        $role = $user->roles()->create([
            'name'      => 'admin',
            'hierarchy' => 1,
        ]);

        $role->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line

        $role->abilities()->sync(Ability::pluck('id')->toArray()); // @phpstan-ignore-line

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
            $b = \App\Models\Brand::query()->create(['name' => $brand]);
            $b->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line
            $brandIds[$brand] = $b->id;
        }

        $types = [
            'Carro',
            'Motocicleta',
        ];

        $typeIds = [];

        foreach ($types as $type) {
            $t = \App\Models\VehicleType::query()->create(['name' => $type]);
            $t->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line
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
            $m->update(['tenant_id' => $this->record->id]); // @phpstan-ignore-line
        }
    }
}
