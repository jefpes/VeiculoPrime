<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Company::create([
            'name'      => 'Motor Market',
            'opened_in' => '2021-07-02',
            'cnpj'      => '99.999.999/9999-99',
            'about'     => 'Somos uma empresa de venda de veículos, na qual presamos pela qualidade e satisfação do cliente.',
            'phone'     => '(85) 99999-9999',
            'email'     => 'google@gmail.com',
            'x'         => 'x.com',
            'instagram' => 'instagram.com',
            'facebook'  => 'facebook.com',
            'linkedin'  => 'linkedin.com',
            'youtube'   => 'youtube.com',
            'whatsapp'  => 'whatsapp.com',
        ])->addresses()->create([
            'zip_code'     => '99999-999',
            'street'       => 'Rua dos Bobos',
            'number'       => '0',
            'neighborhood' => 'Bairro dos Bobos',
            'city'         => 'Fortaleza',
            'state'        => 'CE',
        ]);

    }
}
