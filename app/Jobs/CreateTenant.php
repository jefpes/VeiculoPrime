<?php

namespace App\Jobs;

use App\Enums\Permission;
use App\Models\{Ability, Role, Settings, Store, Tenant, User};
use Database\Seeders\{AbilitySeeder, AccessorySeeder, BrandSeeder, ExtraSeeder, VehicleModelSeeder, VehicleTypeSeeder};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class CreateTenant implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Tenant $tenant)
    {
    }

    public function handle(): void
    {
        $this->tenant->run(function () {
            collect([
                AbilitySeeder::class,
                BrandSeeder::class,
                VehicleTypeSeeder::class,
                VehicleModelSeeder::class,
                AccessorySeeder::class,
                ExtraSeeder::class,
            ])->each(fn ($seeder) => (new $seeder())->run());

            $user        = User::query()->create(['name' => $this->tenant->name, 'email' => $this->tenant->email, 'password' => $this->tenant->password]);
            $maintenance = User::query()->create(['name' => 'maintenance', 'email' => 'maintenance@admin.com', 'password' => 'maintenance']);

            $store = Store::query()->create(['name' => $this->tenant->name, 'slug' => Str::slug($this->tenant->name)]);
            $store->users()->attach([$user->id, $maintenance->id]);

            Settings::query()->create(['name' => $this->tenant->name]);

            $this->createMasterRole($maintenance);
            $this->createAdminRole($user);
            $this->createSellerRole();
            $this->createBuyerRole();
            $this->createEmployeeRole();
        });
    }

    private function createMasterRole(User $user): void
    {
        $role = Role::query()->create(['name' => 'master', 'hierarchy' => 0]);
        $role->users()->attach($user->id);
        $role->abilities()->sync(Ability::query()->pluck('id')->toArray());
    }

    private function createAdminRole(User $user): void
    {
        $role = Role::query()->create(['name' => 'admin', 'hierarchy' => 1]);
        $role->users()->attach($user->id);
        $role->abilities()->sync(Ability::query()->pluck('id')->toArray());
    }

    private function createSellerRole(): void
    {
        $permissions = [
            Permission::VEHICLE_READ,
            Permission::VEHICLE_PHOTO_READ,
            Permission::PEOPLE_READ,
            Permission::PEOPLE_CREATE,
            Permission::PEOPLE_UPDATE,
            Permission::SALE_CREATE,
            Permission::SALE_READ,
            Permission::SALE_UPDATE,
            Permission::INSTALLMENT_READ,
            Permission::BRAND_READ,
            Permission::VEHICLE_MODEL_READ,
            Permission::VEHICLE_TYPE_READ,
        ];

        $role = Role::query()->create(['name' => 'vendedor', 'hierarchy' => 2]);
        $role->abilities()->sync(Ability::query()->whereIn('name', $permissions)->pluck('id')->toArray());
    }

    private function createBuyerRole(): void
    {
        $permissions = [
            Permission::BRAND_CREATE,
            Permission::BRAND_READ,
            Permission::BRAND_UPDATE,
            Permission::BRAND_DELETE,
            Permission::VEHICLE_MODEL_CREATE,
            Permission::VEHICLE_MODEL_READ,
            Permission::VEHICLE_MODEL_UPDATE,
            Permission::VEHICLE_MODEL_DELETE,
            Permission::VEHICLE_TYPE_CREATE,
            Permission::VEHICLE_TYPE_READ,
            Permission::VEHICLE_TYPE_UPDATE,
            Permission::VEHICLE_TYPE_DELETE,
            Permission::VEHICLE_CREATE,
            Permission::VEHICLE_READ,
            Permission::VEHICLE_UPDATE,
            Permission::VEHICLE_DELETE,
            Permission::VEHICLE_PHOTO_CREATE,
            Permission::VEHICLE_PHOTO_READ,
            Permission::VEHICLE_PHOTO_UPDATE,
            Permission::VEHICLE_PHOTO_DELETE,
            Permission::VEHICLE_EXPENSE_CREATE,
            Permission::VEHICLE_EXPENSE_READ,
            Permission::VEHICLE_EXPENSE_UPDATE,
            Permission::VEHICLE_EXPENSE_DELETE,
            Permission::PEOPLE_READ,
            Permission::PEOPLE_CREATE,
            Permission::PEOPLE_UPDATE,
        ];

        $role = Role::query()->create(['name' => 'comprador', 'hierarchy' => 2]);
        $role->abilities()->sync(Ability::query()->whereIn('name', $permissions)->pluck('id')->toArray());
    }

    private function createEmployeeRole(): void
    {
        $permissions = [
            Permission::USER_READ,
            Permission::BRAND_READ,
            Permission::VEHICLE_MODEL_READ,
            Permission::VEHICLE_TYPE_READ,
            Permission::VEHICLE_READ,
            Permission::VEHICLE_PHOTO_READ,
            Permission::STORE_PHOTO_READ,
            Permission::STORE_PHOTO_CREATE,
            Permission::STORE_PHOTO_UPDATE,
            Permission::STORE_PHOTO_DELETE,
            Permission::PEOPLE_CREATE,
            Permission::PEOPLE_READ,
            Permission::PEOPLE_UPDATE,
            Permission::PEOPLE_PHOTO_READ,
            Permission::PEOPLE_PHOTO_CREATE,
            Permission::PEOPLE_PHOTO_UPDATE,
            Permission::EMPLOYEE_CREATE,
            Permission::EMPLOYEE_READ,
            Permission::EMPLOYEE_UPDATE,
            Permission::SALE_READ,
            Permission::INSTALLMENT_READ,
            Permission::VEHICLE_EXPENSE_CREATE,
            Permission::VEHICLE_EXPENSE_READ,
            Permission::VEHICLE_EXPENSE_UPDATE,
            Permission::VEHICLE_EXPENSE_DELETE,
            Permission::PAYMENT_RECEIVE,
            Permission::PAYMENT_UNDO,
        ];

        $role = Role::query()->create(['name' => 'administrativo', 'hierarchy' => 2]);
        $role->abilities()->sync(Ability::query()->whereIn('name', $permissions)->pluck('id')->toArray());
    }
}
