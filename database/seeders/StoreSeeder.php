<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'name'         => 'Loja ' . rand(1, 10),
            'slug'         => 'loja-' . rand(1, 10),
            'zip_code'     => '99999-999',
            'street'       => 'Rua Teste',
            'number'       => '1234',
            'neighborhood' => 'Neighborhood',
            'city'         => 'Pentecoste',
            'state'        => 'Ceará',
            'complement'   => 'Complement test',
        ])->phones()->create([
            'type'   => 'Casa',
            'ddi'    => '55',
            'ddd'    => '88',
            'number' => '99999-9999',
        ]);
    }
}
