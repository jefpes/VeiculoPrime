<?php

namespace Database\Seeders;

use App\Models\{User};
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        User::create([
            'tenant_id'         => $tenant_id,
            'name'              => 'admin',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => '$2y$12$./r6VDrmKTNOxFte58tY..03PmNJgc856574gU8toIftu.KZ6Scwi',
            'remember_token'    => 'ulju8vGmyW7Ju2YXZLhYradlbIBVK1kUWG7Moow0ENieWYwbSKpiXJSfNMXc',
        ]);
    }
}
