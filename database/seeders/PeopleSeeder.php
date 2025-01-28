<?php

namespace Database\Seeders;

use App\Models\{People};
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $photo = "people_$i.webp";
            $model = People::factory(state: ['tenant_id' => $tenant_id])
                        ->withAddress()
                        ->withAffiliate()
                        ->withPhone()
                        ->withEmployee()
                        ->create();

            $folder = "photos/people";

            // Desabilitar eventos para a criação da foto
            \App\Models\Photo::withoutEvents(function () use ($model, $folder, $photo) {
                $model->photos()->create([
                    'path' => "$folder/$photo",
                ]);
            });
        }
    }
}
