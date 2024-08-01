<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\{Ability, User};
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
            'password'          => Hash::make('admin'),
            'remember_token'    => Str::random(10),
        ]);

        $role = $user->roles()->create([
            'name'      => 'admin',
            'hierarchy' => 1,
        ]);

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        $this->call([CitySeeder::class, BrandSeeder::class, VehicleTypeSeeder::class,  VehicleModelSeeder::class, CompanySeeder::class]);
        $this->call([ClientSeeder::class, SuppliersSeeder::class, VehicleSeeder::class, SalesSeeder::class, VehicleExpenseSeeder::class, EmployeeSeeder::class]);
    }
}
