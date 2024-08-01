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

            $folder = "client_photos";

            $client->photos()->create([
                'photo_name' => $photo,
                'format'     => 'webp',
                'full_path'  => base_path("storage/app/$folder/$photo"),
                'path'       => "storage/$folder/$photo",
            ]);
        }
    }
}
