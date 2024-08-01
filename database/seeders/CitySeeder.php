<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Apuiares',
            'Aquiraz',
            'Caucaia',
            'Chorozinho',
            'Curu',
            'Eusébio',
            'General Sampaio',
            'Guaiúba',
            'Horizonte',
            'Irauçuba',
            'Itaitinga',
            'Itapajé',
            'Maracanaú',
            'Maranguape',
            'Pacatuba',
            'Pacoti',
            'Palmácia',
            'Paraipaba',
            'Paracuru',
            'Pentecoste',
            'Pindoretama',
            'Redenção',
            'São Gonçalo do Amarante',
            'Tejuçuoca',
            'Trairi',
            'Tururu',
            'Umirim',
            'Uruburetama',
        ];

        foreach ($cities as $city) {
            City::create(['name' => $city]);
        }
    }
}
