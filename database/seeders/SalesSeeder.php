<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'purchase_date'    => '2024-02-03',
            'purchase_price'   => 10500.00,
            'sale_price'       => 13000.00,
            'vehicle_model_id' => 34,
            'year_one'         => 2015,
            'year_two'         => 2016,
            'km'               => 25000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'transmission'     => 'AUTOMÁTICA',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0101',
            'renavam'          => '000000101',
            'chassi'           => '000000101',
            'sold_date'        => '2024-03-01',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ])->sale()->create(
            [
                'user_id'        => 2,
                'client_id'      => rand(1, 10),
                'payment_method' => 'DINHEIRO',
                'status'         => 'PAGO',
                'date_sale'      => '2024-03-01',
                'date_payment'   => '2024-03-01',
                'discount'       => 200,
                'total'          => 12800,
            ]
        );

        Vehicle::create([
            'purchase_date'    => '2024-02-03',
            'purchase_price'   => 12500.00,
            'sale_price'       => 16000.00,
            'vehicle_model_id' => 34,
            'year_one'         => 2018,
            'year_two'         => 2018,
            'km'               => 25000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'transmission'     => 'AUTOMÁTICA',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0102',
            'renavam'          => '000000102',
            'chassi'           => '000000102',
            'sold_date'        => '2024-03-03',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ])->sale()->create(
            [
                'user_id'        => 2,
                'client_id'      => rand(1, 10),
                'payment_method' => 'DINHEIRO',
                'status'         => 'PAGO',
                'date_sale'      => '2024-03-03',
                'date_payment'   => '2024-03-03',
                'total'          => 16000,
            ]
        );

        Vehicle::create([
            'purchase_date'    => '2024-03-01',
            'purchase_price'   => 9500.00,
            'sale_price'       => 12000.00,
            'vehicle_model_id' => 34,
            'year_one'         => 2014,
            'year_two'         => 2014,
            'km'               => 20000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'transmission'     => 'AUTOMÁTICA',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0103',
            'renavam'          => '000000103',
            'chassi'           => '000000103',
            'sold_date'        => '2024-03-04',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ])->sale()->create(
            [
                'user_id'        => 2,
                'client_id'      => rand(1, 10),
                'payment_method' => 'PIX',
                'status'         => 'PAGO',
                'date_sale'      => '2024-03-04',
                'date_payment'   => '2024-03-04',
                'discount'       => 200,
                'total'          => 12800,
            ]
        );

        Vehicle::create([
            'purchase_date'    => '2024-03-03',
            'purchase_price'   => 10000.00,
            'sale_price'       => 13000.00,
            'vehicle_model_id' => 34,
            'year_one'         => 2015,
            'year_two'         => 2016,
            'km'               => 25000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'transmission'     => 'AUTOMÁTICA',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0104',
            'renavam'          => '000000104',
            'chassi'           => '000000104',
            'sold_date'        => '2024-03-07',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ])->sale()->create(
            [
                'user_id'             => 2,
                'client_id'           => rand(1, 10),
                'payment_method'      => 'CREDIÁRIO PRÓPRIO',
                'status'              => 'PENDENTE',
                'date_sale'           => '2024-03-07',
                'date_payment'        => '2024-03-07',
                'number_installments' => 2,
                'discount'            => 0,
                'total'               => 13000,
            ]
        )->paymentInstallments()->createMany([
            [
                'due_date' => '2024-04-07',
                'value'    => 6500,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-05-07',
                'value'    => 6500,
                'status'   => 'PENDENTE',
            ],
        ]);

        Vehicle::create([
            'purchase_date'    => '2024-03-03',
            'purchase_price'   => 40500.00,
            'sale_price'       => 46000.00,
            'vehicle_model_id' => 1,
            'year_one'         => 2015,
            'year_two'         => 2016,
            'km'               => 26000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'transmission'     => 'AUTOMÁTICA',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0105',
            'renavam'          => '000000105',
            'chassi'           => '000000105',
            'sold_date'        => '2024-03-07',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ])->sale()->create(
            [
                'user_id'             => 2,
                'client_id'           => rand(1, 10),
                'payment_method'      => 'CREDIÁRIO PRÓPRIO',
                'status'              => 'PAGO',
                'date_sale'           => '2024-03-07',
                'date_payment'        => '2024-03-07',
                'number_installments' => 3,
                'down_payment'        => 40000,
                'discount'            => 0,
                'total'               => 46000,
            ]
        )->paymentInstallments()->createMany([
            [
                'due_date'       => '2024-04-07',
                'value'          => 2000,
                'status'         => 'PAGO',
                'payment_date'   => '2024-04-07',
                'payment_value'  => 2000,
                'payment_method' => 'CARTÃO DE CRÉDITO',
                'user_id'        => 2,
            ],
            [
                'due_date'       => '2024-05-07',
                'value'          => 2000,
                'status'         => 'PAGO',
                'payment_date'   => '2024-05-07',
                'payment_value'  => 2000,
                'payment_method' => 'DINHEIRO',
                'user_id'        => 2,
            ],
            [
                'due_date'       => '2024-06-07',
                'value'          => 2000,
                'status'         => 'PAGO',
                'payment_date'   => '2024-06-07',
                'payment_value'  => 2000,
                'payment_method' => 'TRANSFERÊNCIA',
                'user_id'        => 2,
            ],
        ]);

        Vehicle::create([
            'purchase_date'    => '2024-03-03',
            'purchase_price'   => 40500.00,
            'sale_price'       => 46000.00,
            'vehicle_model_id' => 1,
            'year_one'         => 2015,
            'year_two'         => 2016,
            'km'               => 26000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'transmission'     => 'AUTOMÁTICA',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0106',
            'renavam'          => '000000106',
            'chassi'           => '000000106',
            'sold_date'        => '2024-03-07',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ])->sale()->create(
            [
                'user_id'             => 2,
                'client_id'           => rand(1, 10),
                'payment_method'      => 'CREDIÁRIO PRÓPRIO',
                'status'              => 'PENDENTE',
                'date_sale'           => '2024-03-07',
                'date_payment'        => '2024-03-07',
                'number_installments' => 3,
                'down_payment'        => 40000,
                'discount'            => 0,
                'total'               => 46000,
            ]
        )->paymentInstallments()->createMany([
            [
                'due_date'       => '2024-04-07',
                'value'          => 2000,
                'status'         => 'PAGO',
                'payment_date'   => '2024-04-07',
                'payment_value'  => 2000,
                'payment_method' => 'CARTÃO DE CRÉDITO',
                'user_id'        => 2,
            ],
            [
                'due_date'       => '2024-05-07',
                'value'          => 2000,
                'status'         => 'PAGO',
                'payment_date'   => '2024-05-07',
                'payment_value'  => 2000,
                'payment_method' => 'DINHEIRO',
                'user_id'        => 2,
            ],
            [
                'due_date'       => '2024-06-07',
                'value'          => 2000,
                'status'         => 'PAGO',
                'payment_date'   => '2024-06-07',
                'payment_value'  => 2000,
                'payment_method' => 'TRANSFERÊNCIA',
                'user_id'        => 2,
            ],
        ]);

        Vehicle::create([
            'purchase_date'    => '2024-04-03',
            'purchase_price'   => 40500.00,
            'sale_price'       => 46000.00,
            'vehicle_model_id' => 1,
            'year_one'         => 2015,
            'year_two'         => 2016,
            'km'               => 26000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'transmission'     => 'AUTOMÁTICA',
            'color'            => 'Preta',
            'plate'            => 'AAA-0107',
            'renavam'          => '000000107',
            'chassi'           => '000000107',
            'sold_date'        => '2024-04-07',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ])->sale()->create(
            [
                'user_id'             => 2,
                'client_id'           => rand(1, 10),
                'payment_method'      => 'CREDIÁRIO PRÓPRIO',
                'status'              => 'PENDENTE',
                'date_sale'           => '2024-04-07',
                'number_installments' => 10,
                'down_payment'        => 30000,
                'discount'            => 0,
                'total'               => 46000,
            ]
        )->paymentInstallments()->createMany([
            [
                'due_date'       => '2024-05-07',
                'value'          => 1600,
                'status'         => 'PAGO',
                'payment_date'   => '2024-05-07',
                'payment_value'  => 1600,
                'payment_method' => 'CARTÃO DE CRÉDITO',
                'user_id'        => 2,
            ],
            [
                'due_date'       => '2024-06-07',
                'value'          => 1600,
                'status'         => 'PAGO',
                'payment_date'   => '2024-06-07',
                'payment_value'  => 1600,
                'payment_method' => 'DINHEIRO',
                'user_id'        => 2,
            ],
            [
                'due_date' => '2024-07-07',
                'value'    => 1600,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-08-07',
                'value'    => 1600,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-09-07',
                'value'    => 1600,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-10-07',
                'value'    => 1600,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-11-07',
                'value'    => 1600,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-12-07',
                'value'    => 1600,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2025-01-07',
                'value'    => 1600,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2025-02-07',
                'value'    => 1600,
                'status'   => 'PENDENTE',
            ],
        ]);

        Vehicle::create([
            'purchase_date'    => '2024-04-15',
            'purchase_price'   => 36500.00,
            'sale_price'       => 46000.00,
            'vehicle_model_id' => 1,
            'year_one'         => 2013,
            'year_two'         => 2013,
            'km'               => 26000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'transmission'     => 'AUTOMÁTICA',
            'color'            => 'Azul',
            'plate'            => 'AAA-0108',
            'renavam'          => '000000108',
            'chassi'           => '000000108',
            'sold_date'        => '2024-05-07',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ])->sale()->create(
            [
                'user_id'             => 2,
                'client_id'           => rand(1, 10),
                'payment_method'      => 'CREDIÁRIO PRÓPRIO',
                'status'              => 'PENDENTE',
                'date_sale'           => '2024-05-07',
                'date_payment'        => '2024-05-07',
                'number_installments' => 8,
                'down_payment'        => 30000,
                'discount'            => 0,
                'total'               => 46000,
            ]
        )->paymentInstallments()->createMany([
            [
                'due_date'       => '2024-06-07',
                'value'          => 2000,
                'status'         => 'PAGO',
                'payment_date'   => '2024-06-07',
                'payment_value'  => 2000,
                'payment_method' => 'CARTÃO DE CRÉDITO',
                'user_id'        => 2,
            ],
            [
                'due_date' => '2024-07-07',
                'value'    => 2000,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-08-07',
                'value'    => 2000,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-09-07',
                'value'    => 2000,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-10-07',
                'value'    => 2000,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-11-07',
                'value'    => 2000,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2024-12-07',
                'value'    => 2000,
                'status'   => 'PENDENTE',
            ],
            [
                'due_date' => '2025-01-07',
                'value'    => 2000,
                'status'   => 'PENDENTE',
            ],
        ]);

    }
}
