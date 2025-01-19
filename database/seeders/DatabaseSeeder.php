<?php

namespace Database\Seeders;

use App\Models\{Ability, Store, Tenant, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $domain = 'exemplo.veiculoprime.test';

        if (env('APP_ENV') === 'production') {
            $domain = 'exemplo.veiculoprime.com.br';
        }

        $tenant = Tenant::create([
            'name'   => 'admin',
            'domain' => $domain,
        ]);

        // Criar o usuário 'master'
        $user = User::create([
            'tenant_id'         => $tenant->id,
            'name'              => 'master',
            'email'             => 'master@admin.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('admin'),
        ]);

        (new StoreSeeder())->run($tenant->id);

        $user->stores()->sync(Store::pluck('id')->toArray());

        // Criar a role 'master'
        $role = $user->roles()->create([
            'name'      => 'master',
            'hierarchy' => 0,
        ]);

        (new AbilitySeeder())->run();

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        $user = User::create([
            'tenant_id'         => $tenant->id,
            'name'              => 'admin',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => '$2y$12$./r6VDrmKTNOxFte58tY..03PmNJgc856574gU8toIftu.KZ6Scwi',
            'remember_token'    => 'ulju8vGmyW7Ju2YXZLhYradlbIBVK1kUWG7Moow0ENieWYwbSKpiXJSfNMXc',
        ]);

        (new StoreSeeder())->run($tenant->id);

        $user->stores()->sync(Store::pluck('id')->toArray());

        $role = $user->roles()->create([
            'name'      => 'admin',
            'hierarchy' => 1,
        ]);

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        (new SettingsSeeder())->run($tenant->id);
        (new BrandSeeder())->run($tenant->id);
        (new VehicleTypeSeeder())->run($tenant->id);
        (new VehicleModelSeeder())->run($tenant->id);
        (new AccessorySeeder())->run();
        (new ExtraSeeder())->run();
        (new PeopleSeeder())->run($tenant->id);
        (new VehicleSeeder())->run($tenant->id);
        (new SalesSeeder())->run($tenant->id);
        (new VehicleExpenseSeeder())->run();
    }
}
