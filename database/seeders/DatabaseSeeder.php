<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            (new ProductionSeeder())->run();

            return;
        }

        (new TestSeeder())->run(name: 'exemplo', domain: 'exemplo');
        (new TestSeeder())->run(name: 'rato', domain: 'rato');
    }
}
