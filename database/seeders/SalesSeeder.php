<?php

namespace Database\Seeders;

use App\Models\{Accessory, Extra, People, Photo, Sale, Store, Vehicle, VehicleModel};
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?string $tenant_id = null): void
    {
        $vehicleModels = VehicleModel::pluck('id', 'name')->toArray();
        $extras        = Extra::all()->pluck('id', 'name');
        $accessories   = Accessory::all()->pluck('id', 'name');
        $sellers       = People::where('supplier', true)->get();
        $clients       = People::where('client', true)->get();
        $buyers        = People::whereHas('employee')->get();
        $suppliers     = People::where('supplier', true)->get();
        $stores        = Store::all();

        $sales = [
            [
                'vehicle' => [
                    'purchase_date'  => '2024-02-03',
                    'purchase_price' => 10500.00,
                    'sale_price'     => 13000.00,
                    'model'          => 'Titan',
                    'year_one'       => 2015,
                    'year_two'       => 2016,
                    'km'             => 25000,
                    'fuel'           => 'FLEX',
                    'engine_power'   => '1.0',
                    'transmission'   => 'AUTOMÁTICA',
                    'color'          => 'Vermelha',
                    'plate'          => 'AAA-0101',
                    'renavam'        => '000000101',
                    'chassi'         => '000000101',
                    'sold_date'      => '2024-03-01',
                    'description'    => 'Veículo em ótimo estado de conservação.',
                    'photos'         => [
                        'photos/vehicle/titan-vermelha-2015-1.webp',
                        'photos/vehicle/titan-vermelha-2015-2.webp',
                        'photos/vehicle/titan-vermelha-2015-3.webp',
                    ],
                ],
                'sale' => [
                    'payment_method' => 'DINHEIRO',
                    'status'         => 'PAGO',
                    'date_sale'      => '2024-03-01',
                    'date_payment'   => '2024-03-01',
                    'discount'       => 200,
                    'total'          => 12800,
                ],
            ],
            [
                'vehicle' => [
                    'purchase_date'  => '2024-02-03',
                    'purchase_price' => 12500.00,
                    'sale_price'     => 16000.00,
                    'model'          => 'Titan',
                    'year_one'       => 2018,
                    'year_two'       => 2018,
                    'km'             => 25000,
                    'fuel'           => 'FLEX',
                    'engine_power'   => '1.0',
                    'transmission'   => 'AUTOMÁTICA',
                    'color'          => 'Vermelha',
                    'plate'          => 'AAA-0102',
                    'renavam'        => '000000102',
                    'chassi'         => '000000102',
                    'sold_date'      => '2024-03-03',
                    'description'    => 'Veículo em ótimo estado de conservação.',
                    'photos'         => [
                        'photos/vehicle/titan-vermelha-2018-1.webp',
                        'photos/vehicle/titan-vermelha-2018-2.webp',
                        'photos/vehicle/titan-vermelha-2018-3.webp',
                    ],
                ],
                'sale' => [
                    'payment_method' => 'DINHEIRO',
                    'status'         => 'PAGO',
                    'date_sale'      => '2024-03-03',
                    'date_payment'   => '2024-03-03',
                    'total'          => 16000,
                ],
            ],
            [
                'vehicle' => [
                    'purchase_date'  => '2024-03-01',
                    'purchase_price' => 9500.00,
                    'sale_price'     => 12000.00,
                    'model'          => 'Titan',
                    'year_one'       => 2014,
                    'year_two'       => 2014,
                    'km'             => 20000,
                    'fuel'           => 'FLEX',
                    'engine_power'   => '1.0',
                    'transmission'   => 'AUTOMÁTICA',
                    'color'          => 'Vermelha',
                    'plate'          => 'AAA-0103',
                    'renavam'        => '000000103',
                    'chassi'         => '000000103',
                    'sold_date'      => '2024-03-04',
                    'description'    => 'Veículo em ótimo estado de conservação.',
                    'photos'         => [
                        'photos/vehicle/titan-vermelha-2014-1.webp',
                        'photos/vehicle/titan-vermelha-2014-2.webp',
                        'photos/vehicle/titan-vermelha-2014-3.webp',
                    ],
                ],
                'sale' => [
                    'payment_method' => 'PIX',
                    'status'         => 'PAGO',
                    'date_sale'      => '2024-03-04',
                    'date_payment'   => '2024-03-04',
                    'discount'       => 200,
                    'total'          => 12800,
                ],
            ],
            [
                'vehicle' => [
                    'purchase_date'  => '2024-03-03',
                    'purchase_price' => 10000.00,
                    'sale_price'     => 13000.00,
                    'model'          => 'Titan',
                    'year_one'       => 2015,
                    'year_two'       => 2016,
                    'km'             => 25000,
                    'fuel'           => 'FLEX',
                    'engine_power'   => '1.0',
                    'transmission'   => 'AUTOMÁTICA',
                    'color'          => 'Vermelha',
                    'plate'          => 'AAA-0104',
                    'renavam'        => '000000104',
                    'chassi'         => '000000104',
                    'sold_date'      => '2024-03-07',
                    'description'    => 'Veículo em ótimo estado de conservação.',
                    'photos'         => [
                        'photos/vehicle/titan-vermelha-2015-1.webp',
                        'photos/vehicle/titan-vermelha-2015-2.webp',
                        'photos/vehicle/titan-vermelha-2015-3.webp',
                    ],
                ],
                'sale' => [
                    'payment_method'      => 'CREDIÁRIO PRÓPRIO',
                    'status'              => 'PENDENTE',
                    'date_sale'           => '2024-03-07',
                    'date_payment'        => '2024-03-07',
                    'number_installments' => 2,
                    'discount'            => 0,
                    'total'               => 13000,
                ],
                'installments' => [
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
                ],
            ],
            [
                'vehicle' => [
                    'purchase_date'  => '2024-03-03',
                    'purchase_price' => 40500.00,
                    'sale_price'     => 46000.00,
                    'model'          => 'Strada',
                    'year_one'       => 2015,
                    'year_two'       => 2016,
                    'km'             => 26000,
                    'fuel'           => 'FLEX',
                    'engine_power'   => '1.0',
                    'transmission'   => 'AUTOMÁTICA',
                    'color'          => 'Vermelha',
                    'plate'          => 'AAA-0105',
                    'renavam'        => '000000105',
                    'chassi'         => '000000105',
                    'sold_date'      => '2024-03-07',
                    'description'    => 'Veículo em ótimo estado de conservação.',
                    'photos'         => [
                        'photos/vehicle/strada-vermelha-2015-1.webp',
                        'photos/vehicle/strada-vermelha-2015-2.webp',
                        'photos/vehicle/strada-vermelha-2015-3.webp',
                    ],
                ],
                'sale' => [
                    'payment_method'      => 'CREDIÁRIO PRÓPRIO',
                    'status'              => 'PAGO',
                    'date_sale'           => '2024-03-07',
                    'date_payment'        => '2024-03-07',
                    'number_installments' => 3,
                    'down_payment'        => 40000,
                    'discount'            => 0,
                    'total'               => 46000,
                ],
                'installments' => [
                    [
                        'due_date'       => '2024-04-07',
                        'value'          => 2000,
                        'status'         => 'PAGO',
                        'payment_date'   => '2024-04-07',
                        'payment_value'  => 2000,
                        'payment_method' => 'CARTÃO DE CRÉDITO',
                    ],
                    [
                        'due_date'       => '2024-05-07',
                        'value'          => 2000,
                        'status'         => 'PAGO',
                        'payment_date'   => '2024-05-07',
                        'payment_value'  => 2000,
                        'payment_method' => 'DINHEIRO',
                    ],
                    [
                        'due_date'       => '2024-06-07',
                        'value'          => 2000,
                        'status'         => 'PAGO',
                        'payment_date'   => '2024-06-07',
                        'payment_value'  => 2000,
                        'payment_method' => 'TRANSFERÊNCIA',
                    ],
                ],
            ],
        ];

        foreach ($sales as $saleData) {
            $vehicle = $this->createVehicle($saleData['vehicle'], $vehicleModels, $stores, $buyers, $suppliers, $accessories, $extras, $tenant_id);
            $sale    = $this->createSale($saleData['sale'], $vehicle, $sellers, $clients, $tenant_id);

            if (isset($saleData['installments'])) {
                $this->createInstallments($saleData['installments'], $sale, $tenant_id);
            }
        }
    }

    private function createVehicle(array $data, array $vehicleModels, $stores, $buyers, $suppliers, $accessories, $extras, $tenant_id): Vehicle
    {
        $vehicle = Vehicle::create([
            'tenant_id'        => $tenant_id,
            'store_id'         => $stores->random()->id,
            'buyer_id'         => $buyers->random()->id,
            'supplier_id'      => $suppliers->random()->id,
            'purchase_date'    => $data['purchase_date'],
            'purchase_price'   => $data['purchase_price'],
            'sale_price'       => $data['sale_price'],
            'vehicle_model_id' => $vehicleModels[$data['model']] ?? null,
            'year_one'         => $data['year_one'],
            'year_two'         => $data['year_two'],
            'km'               => $data['km'],
            'fuel'             => $data['fuel'],
            'engine_power'     => $data['engine_power'],
            'transmission'     => $data['transmission'],
            'color'            => $data['color'],
            'plate'            => $data['plate'],
            'renavam'          => $data['renavam'],
            'chassi'           => $data['chassi'],
            'sold_date'        => $data['sold_date'],
            'description'      => $data['description'],
        ]);

        $vehicle->accessories()->attach($accessories->random(rand(1, 5)));
        $vehicle->extras()->attach($extras->random(rand(1, 5)));

        foreach ($data['photos'] as $photoPath) {
            Photo::withoutEvents(function () use ($vehicle, $photoPath) {
                $vehicle->photos()->create([
                    'path'   => $photoPath,
                    'public' => true,
                ]);
            });
        }

        return $vehicle;
    }

    private function createSale(array $data, Vehicle $vehicle, $sellers, $clients, $tenant_id): Sale
    {
        return $vehicle->sale()->create([
            'tenant_id'           => $tenant_id,
            'store_id'            => $vehicle->store_id,
            'seller_id'           => $sellers->random()->id,
            'client_id'           => $clients->random()->id,
            'payment_method'      => $data['payment_method'],
            'status'              => $data['status'],
            'date_sale'           => $data['date_sale'],
            'date_payment'        => $data['date_payment'] ?? null,
            'number_installments' => $data['number_installments'] ?? null,
            'down_payment'        => $data['down_payment'] ?? null,
            'discount'            => $data['discount'] ?? 0,
            'total'               => $data['total'],
        ]);
    }

    private function createInstallments(array $installments, Sale $sale, $tenant_id): void
    {
        foreach ($installments as $installmentData) {
            $sale->paymentInstallments()->create(array_merge(
                $installmentData,
                [
                    'store_id'  => $sale->store_id,
                    'tenant_id' => $tenant_id,
                ]
            ));
        }
    }
}
