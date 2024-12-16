<?php

namespace Database\Seeders;

use App\Models\{People};
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $photo = "people_$i.webp";
            $model = People::factory()
                        ->withAddress()
                        ->withAffiliate()
                        ->withPhone()
                        ->withClient()
                        ->withSupplier()
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
