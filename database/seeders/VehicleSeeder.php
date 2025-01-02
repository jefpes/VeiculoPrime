<?php

namespace Database\Seeders;

use App\Models\{Accessory, Extra, People, Photo, Store, Vehicle, VehicleModel};
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleModels = VehicleModel::pluck('id', 'name')->toArray();
        $extras        = Extra::all()->pluck('id', 'name');
        $accessories   = Accessory::all()->pluck('id', 'name');
        $buyers        = People::whereHas('employee')->get();
        $suppliers     = People::where('supplier', true)->get();
        $stores        = Store::all();

        $vehicles = [
            [
                'purchase_date'  => '2024-05-24',
                'fipe_price'     => 48000.00,
                'purchase_price' => 40000.00,
                'sale_price'     => 48000.00,
                'model'          => 'Corolla',
                'year_one'       => 2014,
                'year_two'       => 2014,
                'km'             => 34000,
                'engine_power'   => '1.8',
                'fuel'           => 'FLEX',
                'steering'       => 'HIDRÁULICA',
                'transmission'   => 'AUTOMÁTICA',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Branco',
                'plate'          => 'AAA-0001',
                'chassi'         => '000000001',
                'renavam'        => '000000001',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/corrola-branco-2014-1.webp',
                    'photos/vehicle/corrola-branco-2014-2.webp',
                    'photos/vehicle/corrola-branco-2014-3.webp',
                    'photos/vehicle/corrola-branco-2014-4.webp',
                    'photos/vehicle/corrola-branco-2014-5.webp',
                    'photos/vehicle/corrola-branco-2014-6.webp',
                    'photos/vehicle/corrola-branco-2014-7.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-04-02',
                'fipe_price'     => 15000.00,
                'purchase_price' => 11000.00,
                'sale_price'     => 15000.00,
                'model'          => 'Bros',
                'year_one'       => 2015,
                'year_two'       => 2015,
                'km'             => 10123,
                'fuel'           => 'FLEX',
                'engine_power'   => '160cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Branco',
                'plate'          => 'AAA-0002',
                'chassi'         => '000000002',
                'renavam'        => '000000002',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/bros-branca-2015-1.webp',
                    'photos/vehicle/bros-branca-2015-2.webp',
                    'photos/vehicle/bros-branca-2015-3.webp',
                    'photos/vehicle/bros-branca-2015-4.webp',
                    'photos/vehicle/bros-branca-2015-5.webp',
                ],
            ],
            [
                'purchase_date'     => '2024-05-01',
                'fipe_price'        => 15000.00,
                'purchase_price'    => 10500.00,
                'sale_price'        => 14000.00,
                'promotional_price' => 13500.00,
                'model'             => 'Bros',
                'year_one'          => 2015,
                'year_two'          => 2015,
                'km'                => 16000,
                'fuel'              => 'FLEX',
                'engine_power'      => '160cc',
                'transmission'      => 'MANUAL',
                'color'             => 'Preta',
                'plate'             => 'AAA-0003',
                'chassi'            => '000000003',
                'renavam'           => '000000003',
                'description'       => 'Veículo em ótimo estado de conservação.',
                'photos'            => [
                    'photos/vehicle/bros-preta-2015-1.webp',
                    'photos/vehicle/bros-preta-2015-2.webp',
                    'photos/vehicle/bros-preta-2015-3.webp',
                    'photos/vehicle/bros-preta-2015-4.webp',
                    'photos/vehicle/bros-preta-2015-5.webp',
                ],
            ],
            [
                'purchase_date'     => '2024-05-01',
                'fipe_price'        => 15000.00,
                'purchase_price'    => 10700.00,
                'sale_price'        => 15000.00,
                'promotional_price' => 14500.00,
                'model'             => 'Bros',
                'year_one'          => 2015,
                'year_two'          => 2015,
                'km'                => 16000,
                'fuel'              => 'FLEX',
                'engine_power'      => '160cc',
                'transmission'      => 'MANUAL',
                'color'             => 'Vermelha',
                'plate'             => 'AAA-0004',
                'chassi'            => '000000004',
                'renavam'           => '000000004',
                'description'       => 'Veículo em ótimo estado de conservação.',
                'photos'            => [
                    'photos/vehicle/bros-vermelha-2015-1.webp',
                    'photos/vehicle/bros-vermelha-2015-2.webp',
                    'photos/vehicle/bros-vermelha-2015-3.webp',
                    'photos/vehicle/bros-vermelha-2015-4.webp',
                    'photos/vehicle/bros-vermelha-2015-5.webp',
                ],
            ],
            [
                'purchase_date'     => '2024-06-01',
                'fipe_price'        => 23000.00,
                'purchase_price'    => 17000.00,
                'sale_price'        => 23000.00,
                'promotional_price' => 22000.00,
                'model'             => 'Celta',
                'year_one'          => 2012,
                'year_two'          => 2012,
                'km'                => 41000,
                'fuel'              => 'FLEX',
                'engine_power'      => '1.0',
                'steering'          => 'HIDRÁULICA',
                'transmission'      => 'MANUAL',
                'doors'             => '4',
                'seats'             => '5',
                'color'             => 'Preto',
                'plate'             => 'AAA-0005',
                'renavam'           => '000000005',
                'chassi'            => '000000005',
                'description'       => 'Veículo em ótimo estado de conservação.',
                'photos'            => [
                    'photos/vehicle/celta-preto-2012-1.webp',
                    'photos/vehicle/celta-preto-2012-2.webp',
                    'photos/vehicle/celta-preto-2012-3.webp',
                    'photos/vehicle/celta-preto-2012-4.webp',
                    'photos/vehicle/celta-preto-2012-5.webp',
                    'photos/vehicle/celta-preto-2012-6.webp',
                    'photos/vehicle/celta-preto-2012-7.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-04-03',
                'fipe_price'     => 23000.00,
                'purchase_price' => 19000.00,
                'sale_price'     => 23000.00,
                'model'          => 'Celta',
                'year_one'       => 2013,
                'year_two'       => 2013,
                'km'             => 23000,
                'fuel'           => 'FLEX',
                'engine_power'   => '1.0',
                'steering'       => 'HIDRÁULICA',
                'transmission'   => 'MANUAL',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Vermelho',
                'plate'          => 'AAA-0006',
                'chassi'         => '000000006',
                'renavam'        => '000000006',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/celta-vermelho-2013-1.webp',
                    'photos/vehicle/celta-vermelho-2013-2.webp',
                    'photos/vehicle/celta-vermelho-2013-3.webp',
                    'photos/vehicle/celta-vermelho-2013-4.webp',
                    'photos/vehicle/celta-vermelho-2013-5.webp',
                    'photos/vehicle/celta-vermelho-2013-6.webp',
                    'photos/vehicle/celta-vermelho-2013-7.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-03-01',
                'fipe_price'     => 112000.00,
                'purchase_price' => 90000.00,
                'sale_price'     => 110000.00,
                'model'          => 'Corolla',
                'year_one'       => 2021,
                'year_two'       => 2021,
                'km'             => 21021,
                'fuel'           => 'FLEX',
                'engine_power'   => '2.0',
                'steering'       => 'HIDRÁULICA',
                'transmission'   => 'AUTOMÁTICA',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Preto',
                'plate'          => 'AAA-0007',
                'renavam'        => '000000007',
                'chassi'         => '000000007',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/corrola-preto-2021-1.webp',
                    'photos/vehicle/corrola-preto-2021-2.webp',
                    'photos/vehicle/corrola-preto-2021-3.webp',
                    'photos/vehicle/corrola-preto-2021-4.webp',
                    'photos/vehicle/corrola-preto-2021-5.webp',
                    'photos/vehicle/corrola-preto-2021-6.webp',
                ],
            ],
            [
                'purchase_date'     => '2024-03-22',
                'fipe_price'        => 81000.00,
                'purchase_price'    => 70000.00,
                'sale_price'        => 81000.00,
                'promotional_price' => 80000.00,
                'model'             => 'Corolla',
                'year_one'          => 2016,
                'year_two'          => 2017,
                'km'                => 40123,
                'fuel'              => 'FLEX',
                'engine_power'      => '2.0',
                'steering'          => 'HIDRÁULICA',
                'transmission'      => 'AUTOMÁTICA',
                'doors'             => '4',
                'seats'             => '5',
                'color'             => 'Vermelho',
                'plate'             => 'AAA-0008',
                'renavam'           => '000000008',
                'chassi'            => '000000008',
                'description'       => 'Veículo em ótimo estado de conservação.',
                'photos'            => [
                    'photos/vehicle/corrola-vermelho-2016-1.webp',
                    'photos/vehicle/corrola-vermelho-2016-2.webp',
                    'photos/vehicle/corrola-vermelho-2016-3.webp',
                    'photos/vehicle/corrola-vermelho-2016-4.webp',
                    'photos/vehicle/corrola-vermelho-2016-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-05-01',
                'fipe_price'     => 15000.00,
                'purchase_price' => 13000.00,
                'sale_price'     => 15000.00,
                'model'          => 'Crosser',
                'year_one'       => 2020,
                'year_two'       => 2021,
                'km'             => 17000,
                'fuel'           => 'FLEX',
                'engine_power'   => '150cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Branca',
                'plate'          => 'AAA-0009',
                'renavam'        => '000000009',
                'chassi'         => '000000009',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/crosser-branca-2020-1.webp',
                    'photos/vehicle/crosser-branca-2020-2.webp',
                    'photos/vehicle/crosser-branca-2020-3.webp',
                    'photos/vehicle/crosser-branca-2020-4.webp',
                    'photos/vehicle/crosser-branca-2020-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2023-12-25',
                'fipe_price'     => 16000.00,
                'purchase_price' => 12000.00,
                'sale_price'     => 15500.00,
                'model'          => 'Crosser',
                'year_one'       => 2020,
                'year_two'       => 2020,
                'km'             => 20120,
                'fuel'           => 'FLEX',
                'engine_power'   => '150cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Preto',
                'plate'          => 'AAA-0025',
                'renavam'        => '000000025',
                'chassi'         => '000000025',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/crosser-preta-2020-1.webp',
                    'photos/vehicle/crosser-preta-2020-2.webp',
                    'photos/vehicle/crosser-preta-2020-3.webp',
                    'photos/vehicle/crosser-preta-2020-4.webp',
                    'photos/vehicle/crosser-preta-2020-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-01-02',
                'fipe_price'     => 40600.00,
                'purchase_price' => 32000.00,
                'sale_price'     => 40000.00,
                'model'          => 'HB20',
                'year_one'       => 2017,
                'year_two'       => 2018,
                'km'             => 20120,
                'fuel'           => 'FLEX',
                'engine_power'   => '1.6',
                'steering'       => 'HIDRÁULICA',
                'transmission'   => 'AUTOMÁTICA',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Preto',
                'plate'          => 'AAA-0010',
                'renavam'        => '000000010',
                'chassi'         => '000000010',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/hb20-preto-2017-1.webp',
                    'photos/vehicle/hb20-preto-2017-2.webp',
                    'photos/vehicle/hb20-preto-2017-3.webp',
                    'photos/vehicle/hb20-preto-2017-4.webp',
                    'photos/vehicle/hb20-preto-2017-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-01-04',
                'fipe_price'     => 35000.00,
                'purchase_price' => 28000.00,
                'sale_price'     => 35000.00,
                'model'          => 'HB20',
                'year_one'       => 2015,
                'year_two'       => 2015,
                'km'             => 20120,
                'fuel'           => 'FLEX',
                'engine_power'   => '1.6',
                'steering'       => 'HIDRÁULICA',
                'transmission'   => 'AUTOMÁTICA',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Vermelho',
                'plate'          => 'AAA-0011',
                'renavam'        => '000000011',
                'chassi'         => '000000011',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/hb20-vermelho-2015-1.webp',
                    'photos/vehicle/hb20-vermelho-2015-2.webp',
                    'photos/vehicle/hb20-vermelho-2015-3.webp',
                    'photos/vehicle/hb20-vermelho-2015-4.webp',
                    'photos/vehicle/hb20-vermelho-2015-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-02-04',
                'fipe_price'     => 152000.00,
                'purchase_price' => 120000.00,
                'sale_price'     => 153000.00,
                'model'          => 'Hilux',
                'year_one'       => 2017,
                'year_two'       => 2017,
                'km'             => 42000,
                'fuel'           => 'DIESEL',
                'engine_power'   => '2.8',
                'steering'       => 'ELETRÍCA',
                'transmission'   => 'AUTOMÁTICA',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Preta',
                'plate'          => 'AAA-0012',
                'renavam'        => '000000012',
                'chassi'         => '000000012',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/hilux-preta-2017-1.webp',
                    'photos/vehicle/hilux-preta-2017-2.webp',
                    'photos/vehicle/hilux-preta-2017-3.webp',
                    'photos/vehicle/hilux-preta-2017-4.webp',
                    'photos/vehicle/hilux-preta-2017-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-05-30',
                'fipe_price'     => 200000.00,
                'purchase_price' => 160000.00,
                'sale_price'     => 180000.00,
                'model'          => 'Hilux',
                'year_one'       => 2022,
                'year_two'       => 2022,
                'km'             => 12000,
                'fuel'           => 'DIESEL',
                'engine_power'   => '2.8',
                'steering'       => 'ELETRÍCA',
                'transmission'   => 'AUTOMÁTICA',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Cereja',
                'plate'          => 'AAA-0013',
                'renavam'        => '000000013',
                'chassi'         => '000000013',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/hilux-cereja-2022-1.webp',
                    'photos/vehicle/hilux-cereja-2022-2.webp',
                    'photos/vehicle/hilux-cereja-2022-3.webp',
                    'photos/vehicle/hilux-cereja-2022-4.webp',
                    'photos/vehicle/hilux-cereja-2022-5.webp',
                    'photos/vehicle/hilux-cereja-2022-6.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-04-01',
                'fipe_price'     => 40000.00,
                'purchase_price' => 30000.00,
                'sale_price'     => 37000.00,
                'model'          => 'Kwid',
                'year_one'       => 2019,
                'year_two'       => 2019,
                'km'             => 14000,
                'fuel'           => 'FLEX',
                'engine_power'   => '1.0',
                'steering'       => 'HIDRÁULICA',
                'transmission'   => 'MANUAL',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Branco',
                'plate'          => 'AAA-0014',
                'renavam'        => '000000014',
                'chassi'         => '000000014',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/kwid-branco-2019-1.webp',
                    'photos/vehicle/kwid-branco-2019-2.webp',
                    'photos/vehicle/kwid-branco-2019-3.webp',
                    'photos/vehicle/kwid-branco-2019-4.webp',
                    'photos/vehicle/kwid-branco-2019-5.webp',
                    'photos/vehicle/kwid-branco-2019-6.webp',
                    'photos/vehicle/kwid-branco-2019-7.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-05-08',
                'fipe_price'     => 33000.00,
                'purchase_price' => 25000.00,
                'sale_price'     => 33000.00,
                'model'          => 'Kwid',
                'year_one'       => 2018,
                'year_two'       => 2018,
                'km'             => 21000,
                'fuel'           => 'FLEX',
                'engine_power'   => '1.0',
                'steering'       => 'HIDRÁULICA',
                'transmission'   => 'MANUAL',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Vermelho',
                'plate'          => 'AAA-0015',
                'renavam'        => '000000015',
                'chassi'         => '000000015',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/kwid-vermelho-2018-1.webp',
                    'photos/vehicle/kwid-vermelho-2018-2.webp',
                    'photos/vehicle/kwid-vermelho-2018-3.webp',
                    'photos/vehicle/kwid-vermelho-2018-4.webp',
                    'photos/vehicle/kwid-vermelho-2018-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-02-04',
                'fipe_price'     => 21000.00,
                'purchase_price' => 13000.00,
                'sale_price'     => 20000.00,
                'model'          => 'Lander',
                'year_one'       => 2020,
                'year_two'       => 2020,
                'km'             => 25000,
                'fuel'           => 'FLEX',
                'engine_power'   => '250cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Azul',
                'plate'          => 'AAA-0016',
                'renavam'        => '000000016',
                'chassi'         => '000000016',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/lander-azul-2020-1.webp',
                    'photos/vehicle/lander-azul-2020-2.webp',
                    'photos/vehicle/lander-azul-2020-3.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-01-03',
                'fipe_price'     => 180000.00,
                'purchase_price' => 120000.00,
                'sale_price'     => 165000.00,
                'model'          => 'S10',
                'year_one'       => 2018,
                'year_two'       => 2018,
                'km'             => 36000,
                'fuel'           => 'DIESEL',
                'engine_power'   => '2.8',
                'steering'       => 'ELETRÍCA',
                'transmission'   => 'AUTOMÁTICA',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Branco',
                'plate'          => 'AAA-0017',
                'renavam'        => '000000017',
                'chassi'         => '000000017',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/s10-branco-2018-1.webp',
                    'photos/vehicle/s10-branco-2018-2.webp',
                    'photos/vehicle/s10-branco-2018-3.webp',
                    'photos/vehicle/s10-branco-2018-4.webp',
                    'photos/vehicle/s10-branco-2018-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-02-01',
                'fipe_price'     => 134000.00,
                'purchase_price' => 100000.00,
                'sale_price'     => 130000.00,
                'model'          => 'S10',
                'year_one'       => 2016,
                'year_two'       => 2016,
                'km'             => 26000,
                'fuel'           => 'DIESEL',
                'engine_power'   => '2.8',
                'steering'       => 'ELETRÍCA',
                'transmission'   => 'AUTOMÁTICA',
                'doors'          => '4',
                'seats'          => '5',
                'color'          => 'Vermelha',
                'plate'          => 'AAA-0018',
                'renavam'        => '000000018',
                'chassi'         => '000000018',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/s10-vermelha-2016-1.webp',
                    'photos/vehicle/s10-vermelha-2016-2.webp',
                    'photos/vehicle/s10-vermelha-2016-3.webp',
                    'photos/vehicle/s10-vermelha-2016-4.webp',
                    'photos/vehicle/s10-vermelha-2016-5.webp',
                    'photos/vehicle/s10-vermelha-2016-6.webp',
                ],
            ],
            [
                'purchase_date'  => '2023-11-12',
                'fipe_price'     => 11500.00,
                'purchase_price' => 8000.00,
                'sale_price'     => 11000.00,
                'model'          => 'Titan',
                'year_one'       => 2012,
                'year_two'       => 2012,
                'km'             => 62000,
                'fuel'           => 'FLEX',
                'engine_power'   => '160cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Azul',
                'plate'          => 'AAA-0019',
                'renavam'        => '000000019',
                'chassi'         => '000000019',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/titan-azul-2012-1.webp',
                    'photos/vehicle/titan-azul-2012-2.webp',
                    'photos/vehicle/titan-azul-2012-3.webp',
                ],
            ],
            [
                'purchase_date'  => '2023-10-01',
                'fipe_price'     => 12000.00,
                'purchase_price' => 8500.00,
                'sale_price'     => 11000.00,
                'model'          => 'Titan',
                'year_one'       => 2012,
                'year_two'       => 2012,
                'km'             => 62000,
                'fuel'           => 'FLEX',
                'engine_power'   => '160cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Vermelha',
                'plate'          => 'AAA-0020',
                'renavam'        => '000000020',
                'chassi'         => '000000020',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/titan-vermelha-2012-1.webp',
                    'photos/vehicle/titan-vermelha-2012-2.webp',
                    'photos/vehicle/titan-vermelha-2012-3.webp',
                    'photos/vehicle/titan-vermelha-2012-4.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-02-03',
                'fipe_price'     => 14000.00,
                'purchase_price' => 10500.00,
                'sale_price'     => 13000.00,
                'model'          => 'Titan',
                'year_one'       => 2015,
                'year_two'       => 2016,
                'km'             => 25000,
                'fuel'           => 'FLEX',
                'engine_power'   => '160cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Vermelha',
                'plate'          => 'AAA-0021',
                'renavam'        => '000000021',
                'chassi'         => '000000021',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/titan-vermelha-2015-1.webp',
                    'photos/vehicle/titan-vermelha-2015-2.webp',
                    'photos/vehicle/titan-vermelha-2015-3.webp',
                    'photos/vehicle/titan-vermelha-2015-4.webp',
                    'photos/vehicle/titan-vermelha-2015-5.webp',
                ],
            ],
            [
                'purchase_date'  => '2024-02-03',
                'fipe_price'     => 18000.00,
                'purchase_price' => 15000.00,
                'sale_price'     => 19000.00,
                'model'          => 'Twister',
                'year_one'       => 2020,
                'year_two'       => 2021,
                'km'             => 25000,
                'fuel'           => 'FLEX',
                'engine_power'   => '250cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Prata',
                'plate'          => 'AAA-0022',
                'renavam'        => '000000022',
                'chassi'         => '000000022',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/twister-prata-2020-1.jpg',
                    'photos/vehicle/twister-prata-2020-2.jpg',
                    'photos/vehicle/twister-prata-2020-3.jpg',
                    'photos/vehicle/twister-prata-2020-4.jpg',
                ],
            ],
            [
                'purchase_date'  => '2024-03-03',
                'fipe_price'     => 18000.00,
                'purchase_price' => 15200.00,
                'sale_price'     => 19000.00,
                'model'          => 'Twister',
                'year_one'       => 2020,
                'year_two'       => 2021,
                'km'             => 25000,
                'fuel'           => 'FLEX',
                'engine_power'   => '250cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Vermelha',
                'plate'          => 'AAA-0023',
                'renavam'        => '000000023',
                'chassi'         => '000000023',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/twister-vermelha-2020-1.jpg',
                    'photos/vehicle/twister-vermelha-2020-2.jpg',
                    'photos/vehicle/twister-vermelha-2020-3.jpg',
                    'photos/vehicle/twister-vermelha-2020-4.jpg',
                ],
            ],
            [
                'purchase_date'  => '2024-03-01',
                'fipe_price'     => 20000.00,
                'purchase_price' => 15500.00,
                'sale_price'     => 19000.00,
                'model'          => 'XRE 190',
                'year_one'       => 2020,
                'year_two'       => 2020,
                'km'             => 25000,
                'fuel'           => 'FLEX',
                'engine_power'   => '250cc',
                'transmission'   => 'MANUAL',
                'color'          => 'Preta',
                'plate'          => 'AAA-0024',
                'renavam'        => '000000024',
                'chassi'         => '000000024',
                'description'    => 'Veículo em ótimo estado de conservação.',
                'photos'         => [
                    'photos/vehicle/xre-preta-2020-1.webp',
                    'photos/vehicle/xre-preta-2020-2.webp',
                    'photos/vehicle/xre-preta-2020-3.webp',
                    'photos/vehicle/xre-preta-2020-4.webp',
                ],
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            $vehicle = Vehicle::create([
                'store_id'          => $stores->random()->id,
                'buyer_id'          => $buyers->random()->id,
                'supplier_id'       => $suppliers->random()->id,
                'purchase_date'     => $vehicleData['purchase_date'],
                'fipe_price'        => $vehicleData['fipe_price'],
                'purchase_price'    => $vehicleData['purchase_price'],
                'sale_price'        => $vehicleData['sale_price'],
                'promotional_price' => $vehicleData['promotional_price'] ?? null,
                'vehicle_model_id'  => $vehicleModels[$vehicleData['model']] ?? null,
                'year_one'          => $vehicleData['year_one'],
                'year_two'          => $vehicleData['year_two'],
                'km'                => $vehicleData['km'],
                'engine_power'      => $vehicleData['engine_power'],
                'fuel'              => $vehicleData['fuel'],
                'steering'          => $vehicleData['steering'] ?? null,
                'transmission'      => $vehicleData['transmission'],
                'doors'             => $vehicleData['doors'] ?? null,
                'seats'             => $vehicleData['seats'] ?? null,
                'color'             => $vehicleData['color'],
                'plate'             => $vehicleData['plate'],
                'chassi'            => $vehicleData['chassi'],
                'renavam'           => $vehicleData['renavam'],
                'description'       => $vehicleData['description'],
            ]);

            $vehicle->accessories()->attach($accessories->random(rand(1, 5)));
            $vehicle->extras()->attach($extras->random(rand(1, 5)));

            foreach ($vehicleData['photos'] as $photoPath) {
                Photo::withoutEvents(function () use ($vehicle, $photoPath) {
                    $vehicle->photos()->create([
                        'path'   => $photoPath,
                        'public' => true,
                    ]);
                });
            }
        }
    }
}
