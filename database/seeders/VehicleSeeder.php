<?php

namespace Database\Seeders;

use App\Models\VehiclePhoto;
use App\Models\{Vehicle};
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $vehicle = Vehicle::create([
            'purchase_date'    => '2024-05-24',
            'fipe_price'       => 48000.00,
            'purchase_price'   => 40000.00,
            'sale_price'       => 48000.00,
            'vehicle_model_id' => 26,
            'year_one'         => 2014,
            'year_two'         => 2014,
            'km'               => 34000,
            'engine_power'     => '1.8',
            'fuel'             => 'FLEX',
            'steering'         => 'HIDRÁULICA',
            'transmission'     => 'AUTOMÁTICA',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X2',
            'color'            => 'Branco',
            'plate'            => 'AAA-0001',
            'chassi'           => '000000001',
            'renavam'          => '000000001',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle) {
            $vehicle->photos()->createMany([
                ['path' => 'vehicle_photos/corrola-branco-2014-1.webp'],
                ['path' => 'vehicle_photos/corrola-branco-2014-2.webp'],
                ['path' => 'vehicle_photos/corrola-branco-2014-3.webp'],
                ['path' => 'vehicle_photos/corrola-branco-2014-4.webp'],
                ['path' => 'vehicle_photos/corrola-branco-2014-5.webp'],
                ['path' => 'vehicle_photos/corrola-branco-2014-6.webp'],
                ['path' => 'vehicle_photos/corrola-branco-2014-7.webp'],
            ]);
        });

        // Primeiro veículo
        $vehicle1 = Vehicle::create([
            'purchase_date'    => '2024-04-02',
            'fipe_price'       => 15000.00,
            'purchase_price'   => 11000.00,
            'sale_price'       => 15000.00,
            'vehicle_model_id' => 33,
            'year_one'         => 2015,
            'year_two'         => 2015,
            'km'               => 10123,
            'fuel'             => 'FLEX',
            'engine_power'     => '160cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Branco',
            'plate'            => 'AAA-0002',
            'chassi'           => '000000002',
            'renavam'          => '000000002',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle1) {
            $vehicle1->photos()->createMany([
                ['path' => 'vehicle_photos/bros-branca-2015-1.webp'],
                ['path' => 'vehicle_photos/bros-branca-2015-2.webp'],
                ['path' => 'vehicle_photos/bros-branca-2015-3.webp'],
                ['path' => 'vehicle_photos/bros-branca-2015-4.webp'],
                ['path' => 'vehicle_photos/bros-branca-2015-5.webp'],
            ]);
        });

        // Segundo veículo
        $vehicle2 = Vehicle::create([
            'purchase_date'     => '2024-05-01',
            'fipe_price'        => 15000.00,
            'purchase_price'    => 10500.00,
            'sale_price'        => 14000.00,
            'promotional_price' => 13500.00,
            'vehicle_model_id'  => 33,
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
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle2) {
            $vehicle2->photos()->createMany([
                ['path' => 'vehicle_photos/bros-preta-2015-1.webp'],
                ['path' => 'vehicle_photos/bros-preta-2015-2.webp'],
                ['path' => 'vehicle_photos/bros-preta-2015-3.webp'],
                ['path' => 'vehicle_photos/bros-preta-2015-4.webp'],
                ['path' => 'vehicle_photos/bros-preta-2015-5.webp'],
            ]);
        });

        // Terceiro veículo
        $vehicle3 = Vehicle::create([
            'purchase_date'     => '2024-05-01',
            'fipe_price'        => 15000.00,
            'purchase_price'    => 10700.00,
            'sale_price'        => 15000.00,
            'promotional_price' => 14500.00,
            'vehicle_model_id'  => 33,
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
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle3) {
            $vehicle3->photos()->createMany([
                ['path' => 'vehicle_photos/bros-vermelha-2015-1.webp'],
                ['path' => 'vehicle_photos/bros-vermelha-2015-2.webp'],
                ['path' => 'vehicle_photos/bros-vermelha-2015-3.webp'],
                ['path' => 'vehicle_photos/bros-vermelha-2015-4.webp'],
                ['path' => 'vehicle_photos/bros-vermelha-2015-5.webp'],
            ]);
        });

        // Quarto veículo
        $vehicle4 = Vehicle::create([
            'purchase_date'     => '2024-06-01',
            'fipe_price'        => 23000.00,
            'purchase_price'    => 17000.00,
            'sale_price'        => 23000.00,
            'promotional_price' => 22000.00,
            'vehicle_model_id'  => 37,
            'year_one'          => 2012,
            'year_two'          => 2012,
            'km'                => 41000,
            'fuel'              => 'FLEX',
            'engine_power'      => '1.0',
            'steering'          => 'HIDRÁULICA',
            'transmission'      => 'MANUAL',
            'doors'             => '4',
            'seats'             => '5',
            'traction'          => '4X2',
            'color'             => 'Preto',
            'plate'             => 'AAA-0005',
            'renavam'           => '000000005',
            'chassi'            => '000000005',
            'description'       => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle4) {
            $vehicle4->photos()->createMany([
                ['path' => 'vehicle_photos/celta-preto-2012-1.webp'],
                ['path' => 'vehicle_photos/celta-preto-2012-2.webp'],
                ['path' => 'vehicle_photos/celta-preto-2012-3.webp'],
                ['path' => 'vehicle_photos/celta-preto-2012-4.webp'],
                ['path' => 'vehicle_photos/celta-preto-2012-5.webp'],
                ['path' => 'vehicle_photos/celta-preto-2012-6.webp'],
                ['path' => 'vehicle_photos/celta-preto-2012-7.webp'],
            ]);
        });

        // Quinto veículo
        $vehicle5 = Vehicle::create([
            'purchase_date'    => '2024-04-03',
            'fipe_price'       => 23000.00,
            'purchase_price'   => 19000.00,
            'sale_price'       => 23000.00,
            'vehicle_model_id' => 37,
            'year_one'         => 2013,
            'year_two'         => 2013,
            'km'               => 23000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'steering'         => 'HIDRÁULICA',
            'transmission'     => 'MANUAL',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X2',
            'color'            => 'Vermelho',
            'plate'            => 'AAA-0006',
            'chassi'           => '000000006',
            'renavam'          => '000000006',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle5) {
            $vehicle5->photos()->createMany([
                ['path' => 'vehicle_photos/celta-vermelho-2013-1.webp'],
                ['path' => 'vehicle_photos/celta-vermelho-2013-2.webp'],
                ['path' => 'vehicle_photos/celta-vermelho-2013-3.webp'],
                ['path' => 'vehicle_photos/celta-vermelho-2013-4.webp'],
                ['path' => 'vehicle_photos/celta-vermelho-2013-5.webp'],
                ['path' => 'vehicle_photos/celta-vermelho-2013-6.webp'],
                ['path' => 'vehicle_photos/celta-vermelho-2013-7.webp'],
            ]);
        });

        // Primeiro veículo
        $vehicle1 = Vehicle::create([
            'purchase_date'    => '2024-03-01',
            'fipe_price'       => 112000.00,
            'purchase_price'   => 90000.00,
            'sale_price'       => 110000.00,
            'vehicle_model_id' => 26,
            'year_one'         => 2021,
            'year_two'         => 2021,
            'km'               => 21021,
            'fuel'             => 'FLEX',
            'engine_power'     => '2.0',
            'steering'         => 'HIDRÁULICA',
            'transmission'     => 'AUTOMÁTICA',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X2',
            'color'            => 'Preto',
            'plate'            => 'AAA-0007',
            'renavam'          => '000000007',
            'chassi'           => '000000007',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle1) {
            $vehicle1->photos()->createMany([
                ['path' => 'vehicle_photos/corrola-preto-2021-1.webp'],
                ['path' => 'vehicle_photos/corrola-preto-2021-2.webp'],
                ['path' => 'vehicle_photos/corrola-preto-2021-3.webp'],
                ['path' => 'vehicle_photos/corrola-preto-2021-4.webp'],
                ['path' => 'vehicle_photos/corrola-preto-2021-5.webp'],
                ['path' => 'vehicle_photos/corrola-preto-2021-6.webp'],
            ]);
        });

        // Segundo veículo
        $vehicle2 = Vehicle::create([
            'purchase_date'     => '2024-03-22',
            'fipe_price'        => 81000.00,
            'purchase_price'    => 70000.00,
            'sale_price'        => 81000.00,
            'promotional_price' => 80000.00,
            'vehicle_model_id'  => 26,
            'year_one'          => 2016,
            'year_two'          => 2017,
            'km'                => 40123,
            'fuel'              => 'FLEX',
            'engine_power'      => '2.0',
            'steering'          => 'HIDRÁULICA',
            'transmission'      => 'AUTOMÁTICA',
            'doors'             => '4',
            'seats'             => '5',
            'traction'          => '4X2',
            'color'             => 'Vermelho',
            'plate'             => 'AAA-0008',
            'renavam'           => '000000008',
            'chassi'            => '000000008',
            'description'       => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle2) {
            $vehicle2->photos()->createMany([
                ['path' => 'vehicle_photos/corrola-vermelho-2016-1.webp'],
                ['path' => 'vehicle_photos/corrola-vermelho-2016-2.webp'],
                ['path' => 'vehicle_photos/corrola-vermelho-2016-3.webp'],
                ['path' => 'vehicle_photos/corrola-vermelho-2016-4.webp'],
                ['path' => 'vehicle_photos/corrola-vermelho-2016-5.webp'],
            ]);
        });

        // Terceiro veículo
        $vehicle3 = Vehicle::create([
            'purchase_date'    => '2024-05-01',
            'fipe_price'       => 15000.00,
            'purchase_price'   => 13000.00,
            'sale_price'       => 15000.00,
            'vehicle_model_id' => 35,
            'year_one'         => 2020,
            'year_two'         => 2021,
            'km'               => 17000,
            'fuel'             => 'FLEX',
            'engine_power'     => '150cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Branca',
            'plate'            => 'AAA-0009',
            'renavam'          => '000000009',
            'chassi'           => '000000009',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle3) {
            $vehicle3->photos()->createMany([
                ['path' => 'vehicle_photos/crosser-branca-2020-1.webp'],
                ['path' => 'vehicle_photos/crosser-branca-2020-2.webp'],
                ['path' => 'vehicle_photos/crosser-branca-2020-3.webp'],
                ['path' => 'vehicle_photos/crosser-branca-2020-4.webp'],
                ['path' => 'vehicle_photos/crosser-branca-2020-5.webp'],
            ]);
        });

        // Primeiro veículo
        $vehicle1 = Vehicle::create([
            'purchase_date'    => '2023-12-25',
            'fipe_price'       => 16000.00,
            'purchase_price'   => 12000.00,
            'sale_price'       => 15500.00,
            'vehicle_model_id' => 35,
            'year_one'         => 2020,
            'year_two'         => 2020,
            'km'               => 20120,
            'fuel'             => 'FLEX',
            'engine_power'     => '150cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Preto',
            'plate'            => 'AAA-0025',
            'renavam'          => '000000025',
            'chassi'           => '000000025',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle1) {
            $vehicle1->photos()->createMany([
                ['path' => 'vehicle_photos/crosser-preta-2020-1.webp'],
                ['path' => 'vehicle_photos/crosser-preta-2020-2.webp'],
                ['path' => 'vehicle_photos/crosser-preta-2020-3.webp'],
                ['path' => 'vehicle_photos/crosser-preta-2020-4.webp'],
                ['path' => 'vehicle_photos/crosser-preta-2020-5.webp'],
            ]);
        });

        // Segundo veículo
        $vehicle2 = Vehicle::create([
            'purchase_date'    => '2024-01-02',
            'fipe_price'       => 40600.00,
            'purchase_price'   => 32000.00,
            'sale_price'       => 40000.00,
            'vehicle_model_id' => 13,
            'year_one'         => 2017,
            'year_two'         => 2018,
            'km'               => 20120,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.6',
            'steering'         => 'HIDRÁULICA',
            'transmission'     => 'AUTOMÁTICA',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X2',
            'color'            => 'Preto',
            'plate'            => 'AAA-0010',
            'renavam'          => '000000010',
            'chassi'           => '000000010',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle2) {
            $vehicle2->photos()->createMany([
                ['path' => 'vehicle_photos/hb20-preto-2017-1.webp'],
                ['path' => 'vehicle_photos/hb20-preto-2017-2.webp'],
                ['path' => 'vehicle_photos/hb20-preto-2017-3.webp'],
                ['path' => 'vehicle_photos/hb20-preto-2017-4.webp'],
                ['path' => 'vehicle_photos/hb20-preto-2017-5.webp'],
            ]);
        });

        // Terceiro veículo
        $vehicle3 = Vehicle::create([
            'purchase_date'    => '2024-01-04',
            'fipe_price'       => 35000.00,
            'purchase_price'   => 28000.00,
            'sale_price'       => 35000.00,
            'vehicle_model_id' => 13,
            'year_one'         => 2015,
            'year_two'         => 2015,
            'km'               => 20120,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.6',
            'steering'         => 'HIDRÁULICA',
            'transmission'     => 'AUTOMÁTICA',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X2',
            'color'            => 'Vermelho',
            'plate'            => 'AAA-0011',
            'renavam'          => '000000011',
            'chassi'           => '000000011',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle3) {
            $vehicle3->photos()->createMany([
                ['path' => 'vehicle_photos/hb20-vermelho-2015-1.webp'],
                ['path' => 'vehicle_photos/hb20-vermelho-2015-2.webp'],
                ['path' => 'vehicle_photos/hb20-vermelho-2015-3.webp'],
                ['path' => 'vehicle_photos/hb20-vermelho-2015-4.webp'],
                ['path' => 'vehicle_photos/hb20-vermelho-2015-5.webp'],
            ]);
        });

        // Quarto veículo
        $vehicle4 = Vehicle::create([
            'purchase_date'    => '2024-02-04',
            'fipe_price'       => 152000.00,
            'purchase_price'   => 120000.00,
            'sale_price'       => 153000.00,
            'vehicle_model_id' => 27,
            'year_one'         => 2017,
            'year_two'         => 2017,
            'km'               => 42000,
            'fuel'             => 'DIESEL',
            'engine_power'     => '2.8',
            'steering'         => 'ELETRÍCA',
            'transmission'     => 'AUTOMÁTICA',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X4',
            'color'            => 'Preta',
            'plate'            => 'AAA-0012',
            'renavam'          => '000000012',
            'chassi'           => '000000012',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle4) {
            $vehicle4->photos()->createMany([
                ['path' => 'vehicle_photos/hilux-preta-2017-1.webp'],
                ['path' => 'vehicle_photos/hilux-preta-2017-2.webp'],
                ['path' => 'vehicle_photos/hilux-preta-2017-3.webp'],
                ['path' => 'vehicle_photos/hilux-preta-2017-4.webp'],
                ['path' => 'vehicle_photos/hilux-preta-2017-5.webp'],
            ]);
        });

        $vehicle1 = Vehicle::create([
            'purchase_date'    => '2024-05-30',
            'fipe_price'       => 200000.00,
            'purchase_price'   => 160000.00,
            'sale_price'       => 180000.00,
            'vehicle_model_id' => 27,
            'year_one'         => 2022,
            'year_two'         => 2022,
            'km'               => 12000,
            'fuel'             => 'DIESEL',
            'engine_power'     => '2.8',
            'steering'         => 'ELETRÍCA',
            'transmission'     => 'AUTOMÁTICA',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X4',
            'color'            => 'Cereja',
            'plate'            => 'AAA-0013',
            'renavam'          => '000000013',
            'chassi'           => '000000013',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle1) {
            $vehicle1->photos()->createMany([
                ['path' => 'vehicle_photos/hilux-cereja-2022-1.webp'],
                ['path' => 'vehicle_photos/hilux-cereja-2022-2.webp'],
                ['path' => 'vehicle_photos/hilux-cereja-2022-3.webp'],
                ['path' => 'vehicle_photos/hilux-cereja-2022-4.webp'],
                ['path' => 'vehicle_photos/hilux-cereja-2022-5.webp'],
                ['path' => 'vehicle_photos/hilux-cereja-2022-6.webp'],
            ]);
        });

        $vehicle2 = Vehicle::create([
            'purchase_date'    => '2024-04-01',
            'fipe_price'       => 40000.00,
            'purchase_price'   => 30000.00,
            'sale_price'       => 37000.00,
            'vehicle_model_id' => 23,
            'year_one'         => 2019,
            'year_two'         => 2019,
            'km'               => 14000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'steering'         => 'HIDRÁULICA',
            'transmission'     => 'MANUAL',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X2',
            'color'            => 'Branco',
            'plate'            => 'AAA-0014',
            'renavam'          => '000000014',
            'chassi'           => '000000014',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle2) {
            $vehicle2->photos()->createMany([
                ['path' => 'vehicle_photos/kwid-branco-2019-1.webp'],
                ['path' => 'vehicle_photos/kwid-branco-2019-2.webp'],
                ['path' => 'vehicle_photos/kwid-branco-2019-3.webp'],
                ['path' => 'vehicle_photos/kwid-branco-2019-4.webp'],
                ['path' => 'vehicle_photos/kwid-branco-2019-5.webp'],
                ['path' => 'vehicle_photos/kwid-branco-2019-6.webp'],
                ['path' => 'vehicle_photos/kwid-branco-2019-7.webp'],
            ]);
        });

        $vehicle3 = Vehicle::create([
            'purchase_date'    => '2024-05-08',
            'fipe_price'       => 33000.00,
            'purchase_price'   => 25000.00,
            'sale_price'       => 33000.00,
            'vehicle_model_id' => 23,
            'year_one'         => 2018,
            'year_two'         => 2018,
            'km'               => 21000,
            'fuel'             => 'FLEX',
            'engine_power'     => '1.0',
            'steering'         => 'HIDRÁULICA',
            'transmission'     => 'MANUAL',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X2',
            'color'            => 'Vermelho',
            'plate'            => 'AAA-0015',
            'renavam'          => '000000015',
            'chassi'           => '000000015',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle3) {
            $vehicle3->photos()->createMany([
                ['path' => 'vehicle_photos/kwid-vermelho-2018-1.webp'],
                ['path' => 'vehicle_photos/kwid-vermelho-2018-2.webp'],
                ['path' => 'vehicle_photos/kwid-vermelho-2018-3.webp'],
                ['path' => 'vehicle_photos/kwid-vermelho-2018-4.webp'],
                ['path' => 'vehicle_photos/kwid-vermelho-2018-5.webp'],
            ]);
        });

        $vehicle1 = Vehicle::create([
            'purchase_date'    => '2024-02-04',
            'fipe_price'       => 21000.00,
            'purchase_price'   => 13000.00,
            'sale_price'       => 20000.00,
            'vehicle_model_id' => 36,
            'year_one'         => 2020,
            'year_two'         => 2020,
            'km'               => 25000,
            'fuel'             => 'FLEX',
            'engine_power'     => '250cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Azul',
            'plate'            => 'AAA-0016',
            'renavam'          => '000000016',
            'chassi'           => '000000016',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle1) {
            $vehicle1->photos()->createMany([
                ['path' => 'vehicle_photos/lander-azul-2020-1.webp'],
                ['path' => 'vehicle_photos/lander-azul-2020-2.webp'],
                ['path' => 'vehicle_photos/lander-azul-2020-3.webp'],
            ]);
        });

        $vehicle2 = Vehicle::create([
            'purchase_date'    => '2024-01-03',
            'fipe_price'       => 180000.00,
            'purchase_price'   => 120000.00,
            'sale_price'       => 165000.00,
            'vehicle_model_id' => 12,
            'year_one'         => 2018,
            'year_two'         => 2018,
            'km'               => 36000,
            'fuel'             => 'DIESEL',
            'engine_power'     => '2.8',
            'steering'         => 'ELETRÍCA',
            'transmission'     => 'AUTOMÁTICA',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X4',
            'color'            => 'Branco',
            'plate'            => 'AAA-0017',
            'renavam'          => '000000017',
            'chassi'           => '000000017',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle2) {
            $vehicle2->photos()->createMany([
                ['path' => 'vehicle_photos/s10-branco-2018-1.webp'],
                ['path' => 'vehicle_photos/s10-branco-2018-2.webp'],
                ['path' => 'vehicle_photos/s10-branco-2018-3.webp'],
                ['path' => 'vehicle_photos/s10-branco-2018-4.webp'],
                ['path' => 'vehicle_photos/s10-branco-2018-5.webp'],
            ]);
        });

        $vehicle3 = Vehicle::create([
            'purchase_date'    => '2024-02-01',
            'fipe_price'       => 134000.00,
            'purchase_price'   => 100000.00,
            'sale_price'       => 130000.00,
            'vehicle_model_id' => 12,
            'year_one'         => 2016,
            'year_two'         => 2016,
            'km'               => 26000,
            'fuel'             => 'DIESEL',
            'engine_power'     => '2.8',
            'steering'         => 'ELETRÍCA',
            'transmission'     => 'AUTOMÁTICA',
            'doors'            => '4',
            'seats'            => '5',
            'traction'         => '4X4',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0018',
            'renavam'          => '000000018',
            'chassi'           => '000000018',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle3) {
            $vehicle3->photos()->createMany([
                ['path' => 'vehicle_photos/s10-vermelha-2016-1.webp'],
                ['path' => 'vehicle_photos/s10-vermelha-2016-2.webp'],
                ['path' => 'vehicle_photos/s10-vermelha-2016-3.webp'],
                ['path' => 'vehicle_photos/s10-vermelha-2016-4.webp'],
                ['path' => 'vehicle_photos/s10-vermelha-2016-5.webp'],
                ['path' => 'vehicle_photos/s10-vermelha-2016-6.webp'],
            ]);
        });

        $vehicle5 = Vehicle::create([
            'purchase_date'    => '2023-11-12',
            'fipe_price'       => 11500.00,
            'purchase_price'   => 8000.00,
            'sale_price'       => 11000.00,
            'vehicle_model_id' => 34,
            'year_one'         => 2012,
            'year_two'         => 2012,
            'km'               => 62000,
            'fuel'             => 'FLEX',
            'engine_power'     => '160cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Azul',
            'plate'            => 'AAA-0019',
            'renavam'          => '000000019',
            'chassi'           => '000000019',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle5) {
            $vehicle5->photos()->createMany([
                ['path' => 'vehicle_photos/titan-azul-2012-1.webp'],
                ['path' => 'vehicle_photos/titan-azul-2012-2.webp'],
                ['path' => 'vehicle_photos/titan-azul-2012-3.webp'],
            ]);
        });

        $vehicle6 = Vehicle::create([
            'purchase_date'    => '2023-10-01',
            'fipe_price'       => 12000.00,
            'purchase_price'   => 8500.00,
            'sale_price'       => 11000.00,
            'vehicle_model_id' => 34,
            'year_one'         => 2012,
            'year_two'         => 2012,
            'km'               => 62000,
            'fuel'             => 'FLEX',
            'engine_power'     => '160cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0020',
            'renavam'          => '000000020',
            'chassi'           => '000000020',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle6) {
            $vehicle6->photos()->createMany([
                ['path' => 'vehicle_photos/titan-vermelha-2012-1.webp'],
                ['path' => 'vehicle_photos/titan-vermelha-2012-2.webp'],
                ['path' => 'vehicle_photos/titan-vermelha-2012-3.webp'],
                ['path' => 'vehicle_photos/titan-vermelha-2012-4.webp'],
            ]);
        });

        $vehicle7 = Vehicle::create([
            'purchase_date'    => '2024-02-03',
            'fipe_price'       => 14000.00,
            'purchase_price'   => 10500.00,
            'sale_price'       => 13000.00,
            'vehicle_model_id' => 34,
            'year_one'         => 2015,
            'year_two'         => 2016,
            'km'               => 25000,
            'fuel'             => 'FLEX',
            'engine_power'     => '160cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0021',
            'renavam'          => '000000021',
            'chassi'           => '000000021',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle7) {
            $vehicle7->photos()->createMany([
                ['path' => 'vehicle_photos/titan-vermelha-2015-1.webp'],
                ['path' => 'vehicle_photos/titan-vermelha-2015-2.webp'],
                ['path' => 'vehicle_photos/titan-vermelha-2015-3.webp'],
                ['path' => 'vehicle_photos/titan-vermelha-2015-4.webp'],
                ['path' => 'vehicle_photos/titan-vermelha-2015-5.webp'],
            ]);
        });

        $vehicle8 = Vehicle::create([
            'purchase_date'    => '2024-02-03',
            'fipe_price'       => 18000.00,
            'purchase_price'   => 15000.00,
            'sale_price'       => 19000.00,
            'vehicle_model_id' => 38,
            'year_one'         => 2020,
            'year_two'         => 2021,
            'km'               => 25000,
            'fuel'             => 'FLEX',
            'engine_power'     => '250cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Prata',
            'plate'            => 'AAA-0022',
            'renavam'          => '000000022',
            'chassi'           => '000000022',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle8) {
            $vehicle8->photos()->createMany([
                ['path' => 'vehicle_photos/twister-prata-2020-1.jpg'],
                ['path' => 'vehicle_photos/twister-prata-2020-2.jpg'],
                ['path' => 'vehicle_photos/twister-prata-2020-3.jpg'],
                ['path' => 'vehicle_photos/twister-prata-2020-4.jpg'],
            ]);
        });

        $vehicle9 = Vehicle::create([
            'purchase_date'    => '2024-03-03',
            'fipe_price'       => 18000.00,
            'purchase_price'   => 15200.00,
            'sale_price'       => 19000.00,
            'vehicle_model_id' => 38,
            'year_one'         => 2020,
            'year_two'         => 2021,
            'km'               => 25000,
            'fuel'             => 'FLEX',
            'engine_power'     => '250cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Vermelha',
            'plate'            => 'AAA-0023',
            'renavam'          => '000000023',
            'chassi'           => '000000023',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle9) {
            $vehicle9->photos()->createMany([
                ['path' => 'vehicle_photos/twister-vermelha-2020-1.jpg'],
                ['path' => 'vehicle_photos/twister-vermelha-2020-2.jpg'],
                ['path' => 'vehicle_photos/twister-vermelha-2020-3.jpg'],
                ['path' => 'vehicle_photos/twister-vermelha-2020-4.jpg'],
            ]);
        });

        $vehicle10 = Vehicle::create([
            'purchase_date'    => '2024-03-01',
            'fipe_price'       => 20000.00,
            'purchase_price'   => 15500.00,
            'sale_price'       => 19000.00,
            'vehicle_model_id' => 39,
            'year_one'         => 2020,
            'year_two'         => 2020,
            'km'               => 25000,
            'fuel'             => 'FLEX',
            'engine_power'     => '250cc',
            'transmission'     => 'MANUAL',
            'color'            => 'Preta',
            'plate'            => 'AAA-0024',
            'renavam'          => '000000024',
            'chassi'           => '000000024',
            'description'      => 'Veículo em ótimo estado de conservação.',
        ]);

        // Desativar eventos temporariamente para evitar renomeação e movimentação
        VehiclePhoto::withoutEvents(function () use ($vehicle10) {
            $vehicle10->photos()->createMany([
                ['path' => 'vehicle_photos/xre-preta-2020-1.webp'],
                ['path' => 'vehicle_photos/xre-preta-2020-2.webp'],
                ['path' => 'vehicle_photos/xre-preta-2020-3.webp'],
                ['path' => 'vehicle_photos/xre-preta-2020-4.webp'],
            ]);
        });

    }
}
