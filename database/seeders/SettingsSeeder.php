<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        \App\Models\Settings::create([
            'tenant_id' => $tenant_id,
            'name'      => 'Motor Market',
            'opened_in' => '2021-07-02',
            'cnpj'      => '99.999.999/9999-99',
            'about'     => 'Somos uma empresa de venda de veículos, na qual prezamos pela qualidade e satisfação do cliente.',
            'email'     => 'google@gmail.com',
            'x'         => 'http://x.com',
            'instagram' => 'http://instagram.com',
            'facebook'  => 'http://facebook.com',
            'linkedin'  => 'http://linkedin.com',
            'youtube'   => 'http://youtube.com',
            'whatsapp'  => 'http://whatsapp.com',
        ]);
    }
}
