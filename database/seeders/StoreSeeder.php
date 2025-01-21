<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        Store::factory(state: ['tenant_id' => $tenant_id])->withPhone()->create();
    }
}
