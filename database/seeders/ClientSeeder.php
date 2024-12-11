<?php

namespace Database\Seeders;

use App\Models\{Client};
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $photo = "client_$i.webp";
            $model = Client::factory()->withAddress()->withAffiliate()->withPhone()->create();

            $folder = "photos/client";

            // Desabilitar eventos para a criação da foto
            \App\Models\Photo::withoutEvents(function () use ($model, $folder, $photo) {
                $model->photos()->create([
                    'path' => "$folder/$photo",
                ]);
            });
        }
    }
}
