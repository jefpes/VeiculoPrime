<?php

namespace Database\Seeders;

use App\Models\{Ability, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
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
            'password'          => '$2y$12$DzxeahiROwwm9RfZbcNstOMOYj1WriCq/RGmdYQ/.mwD/Ot9KIRqG',
        ]);

        $role = $user->roles()->create([
            'name'      => 'master',
            'hierarchy' => 0,
        ]);

        (new AbilitySeeder())->run();

        $role->abilities()->sync(Ability::pluck('id')->toArray());

        (new SettingsSeeder())->run();
        (new AccessorySeeder())->run();
        (new ExtraSeeder())->run();
    }
}
