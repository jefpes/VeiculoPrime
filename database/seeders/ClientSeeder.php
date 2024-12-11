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
            $photo  = "client_$i.webp";
            $client = Client::factory()->withAddress()->create();

            $folder = "photos/client";

            // Desabilitar eventos para a criação da foto
            \App\Models\ClientPhoto::withoutEvents(function () use ($client, $folder, $photo) {
                $client->photos()->create([
                    'path' => "$folder/$photo",
                ]);
            });
        }
    }
}
