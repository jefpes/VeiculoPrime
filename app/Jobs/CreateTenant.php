<?php

namespace App\Jobs;

use App\Models\{Ability, Settings, Store, Tenant, User};
use Database\Seeders\{AbilitySeeder, AccessorySeeder, BrandSeeder, ExtraSeeder, VehicleModelSeeder, VehicleTypeSeeder};
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
            $user = User::query()->create(['name' => $this->tenant->name, 'email' => $this->tenant->email, 'password' => $this->tenant->password]);

            Store::query()->create(['name' => 'Loja 1', 'slug' => 'loja-1'])->users()->attach($user->id);

            (new AbilitySeeder())->run();

            Settings::query()->create(['name' => $this->tenant->name]);

            $role = $user->roles()->create([
                'name'      => 'master',
                'hierarchy' => 0,
            ]);

            $role->abilities()->sync(Ability::query()->pluck('id')->toArray()); //@phpstan-ignore-line

            (new BrandSeeder())->run();
            (new VehicleTypeSeeder())->run();
            (new VehicleModelSeeder())->run();
            (new AccessorySeeder())->run();
            (new ExtraSeeder())->run();
        });
    }
}
