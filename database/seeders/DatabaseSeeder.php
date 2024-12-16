<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\{Ability, Settings, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Criar o usuário 'master'
        $user = User::create([
            'name'              => 'master',
            'email'             => 'master@admin.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('admin'),
            'remember_token'    => Str::random(10),
        ]);

        Settings::create(['user_id' => $user->id]);

        // Criar a role 'master'
        $role = $user->roles()->create([
            'name'      => 'master',
            'hierarchy' => 0,
        ]);

        foreach (Permission::cases() as $permission) {
            $role->abilities()->create(['name' => $permission->value]);
        }

        $user = User::create([
            'name'              => 'admin',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => '$2y$12$p0D7QL40tsmgvEsx1BC32eicJU1BAQRWN39so0xq.oJ5K0XhcJwc2',
            'remember_token'    => 'yLvCEW5zMY',
        ]);

        Settings::create(['user_id' => $user->id]);

        $role = $user->roles()->create([
            'name'      => 'admin',
            'hierarchy' => 1,
        ]);

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        $this->call(CompanySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(VehicleTypeSeeder::class);
        $this->call(VehicleModelSeeder::class);
        $this->call(PeopleSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(SuppliersSeeder::class);
        $this->call(VehicleSeeder::class);
        $this->call(SalesSeeder::class);
        $this->call(VehicleExpenseSeeder::class);
        $this->call(EmployeeSeeder::class);
    }
}
