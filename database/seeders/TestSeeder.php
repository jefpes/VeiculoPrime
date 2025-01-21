<?php

namespace Database\Seeders;

use App\Models\{Ability, Settings, Store, Tenant, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(string $name, string $domain): void
    {
        $tenant = Tenant::create([
            'name'   => $name,
            'domain' => $domain,
        ]);

        if (User::count() === 0) {
            $user = User::create([
                'name'              => 'master',
                'email'             => 'master@admin.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('admin'),
            ]);
        }

        // Criar o usuário 'master'
        $user = User::create([
            'tenant_id'         => $tenant->id,
            'name'              => $name,
            'email'             => "$name@admin.com",
            'email_verified_at' => now(),
            'password'          => Hash::make('admin'),
        ]);

        (new StoreSeeder())->run($tenant->id);
        (new StoreSeeder())->run($tenant->id);

        $user->stores()->sync(Store::pluck('id')->toArray());

        // Criar a role 'master'
        $role = $user->roles()->create([
            'name'      => 'master',
            'hierarchy' => 0,
        ]);

        if (Ability::count() === 0) {
            (new AbilitySeeder())->run();
        }

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        if (Settings::where('tenant_id', null)->count() === 0) {
            (new SettingsSeeder())->run();
        }

        (new SettingsSeeder())->run($tenant->id);
        (new BrandSeeder())->run($tenant->id);
        (new VehicleTypeSeeder())->run($tenant->id);
        (new VehicleModelSeeder())->run($tenant->id);
        (new AccessorySeeder())->run();
        (new ExtraSeeder())->run();
        (new PeopleSeeder())->run($tenant->id);
        (new VehicleSeeder())->run($tenant->id);
        (new SalesSeeder())->run($tenant->id);
        (new VehicleExpenseSeeder())->run($tenant->id);
    }
}
