<?php

namespace Database\Seeders;

use App\Models\{Accessory, Extra, People, Photo, Sale, Store, Vehicle, VehicleModel};
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Executa os seeds do banco de dados.
     */
    public function run(?string $tenant_id = null): void
    {
        $modelosVeiculos = VehicleModel::pluck('id', 'name')->toArray();
        $extras          = Extra::all()->pluck('id', 'name');
        $acessorios      = Accessory::all()->pluck('id', 'name');
        $vendedores      = People::where('supplier', true)->get();
        $clientes        = People::where('client', true)->get();
        $compradores     = People::whereHas('employee')->get();
        $fornecedores    = People::where('supplier', true)->get();
        $lojas           = Store::all();

        $vendas = [
            [
                'veiculo' => [
                    'data_compra'    => '2024-02-03',
                    'preco_compra'   => 10500.00,
                    'preco_venda'    => 13000.00,
                    'modelo'         => 'Titan',
                    'ano_um'         => 2015,
                    'ano_dois'       => 2016,
                    'km'             => 25000,
                    'combustivel'    => 'FLEX',
                    'potencia_motor' => '1.0',
                    'transmissao'    => 'AUTOMÁTICA',
                    'cor'            => 'Vermelha',
                    'placa'          => 'AAA-0101',
                    'renavam'        => '000000101',
                    'chassi'         => '000000101',
                    'data_venda'     => '2024-03-01',
                    'descricao'      => 'Veículo em ótimo estado de conservação.',
                    'fotos'          => [
                        'photos/vehicle/titan-vermelha-2015-1.webp',
                        'photos/vehicle/titan-vermelha-2015-2.webp',
                        'photos/vehicle/titan-vermelha-2015-3.webp',
                    ],
                ],
                'venda' => [
                    'metodo_pagamento' => 'DINHEIRO',
                    'status'           => 'PAGO',
                    'data_venda'       => '2024-03-01',
                    'data_pagamento'   => '2024-03-01',
                    'desconto'         => 200,
                    'total'            => 12800,
                ],
            ],
            [
                'veiculo' => [
                    'data_compra'    => '2024-02-03',
                    'preco_compra'   => 12500.00,
                    'preco_venda'    => 16000.00,
                    'modelo'         => 'Titan',
                    'ano_um'         => 2018,
                    'ano_dois'       => 2018,
                    'km'             => 25000,
                    'combustivel'    => 'FLEX',
                    'potencia_motor' => '1.0',
                    'transmissao'    => 'AUTOMÁTICA',
                    'cor'            => 'Vermelha',
                    'placa'          => 'AAA-0102',
                    'renavam'        => '000000102',
                    'chassi'         => '000000102',
                    'data_venda'     => '2024-03-03',
                    'descricao'      => 'Veículo em ótimo estado de conservação.',
                    'fotos'          => [
                        'photos/vehicle/titan-vermelha-2018-1.webp',
                        'photos/vehicle/titan-vermelha-2018-2.webp',
                        'photos/vehicle/titan-vermelha-2018-3.webp',
                    ],
                ],
                'venda' => [
                    'metodo_pagamento' => 'DINHEIRO',
                    'status'           => 'PAGO',
                    'data_venda'       => '2024-03-03',
                    'data_pagamento'   => '2024-03-03',
                    'total'            => 16000,
                ],
            ],
            [
                'veiculo' => [
                    'data_compra'    => '2024-03-01',
                    'preco_compra'   => 9500.00,
                    'preco_venda'    => 12000.00,
                    'modelo'         => 'Titan',
                    'ano_um'         => 2014,
                    'ano_dois'       => 2014,
                    'km'             => 20000,
                    'combustivel'    => 'FLEX',
                    'potencia_motor' => '1.0',
                    'transmissao'    => 'AUTOMÁTICA',
                    'cor'            => 'Vermelha',
                    'placa'          => 'AAA-0103',
                    'renavam'        => '000000103',
                    'chassi'         => '000000103',
                    'data_venda'     => '2024-03-04',
                    'descricao'      => 'Veículo em ótimo estado de conservação.',
                    'fotos'          => [
                        'photos/vehicle/titan-vermelha-2014-1.webp',
                        'photos/vehicle/titan-vermelha-2014-2.webp',
                        'photos/vehicle/titan-vermelha-2014-3.webp',
                    ],
                ],
                'venda' => [
                    'metodo_pagamento' => 'PIX',
                    'status'           => 'PAGO',
                    'data_venda'       => '2024-03-04',
                    'data_pagamento'   => '2024-03-04',
                    'desconto'         => 200,
                    'total'            => 12800,
                ],
            ],
            [
                'veiculo' => [
                    'data_compra'    => '2024-03-03',
                    'preco_compra'   => 10000.00,
                    'preco_venda'    => 13000.00,
                    'modelo'         => 'Titan',
                    'ano_um'         => 2015,
                    'ano_dois'       => 2016,
                    'km'             => 25000,
                    'combustivel'    => 'FLEX',
                    'potencia_motor' => '1.0',
                    'transmissao'    => 'AUTOMÁTICA',
                    'cor'            => 'Vermelha',
                    'placa'          => 'AAA-0104',
                    'renavam'        => '000000104',
                    'chassi'         => '000000104',
                    'data_venda'     => '2024-03-07',
                    'descricao'      => 'Veículo em ótimo estado de conservação.',
                    'fotos'          => [
                        'photos/vehicle/titan-vermelha-2015-1.webp',
                        'photos/vehicle/titan-vermelha-2015-2.webp',
                        'photos/vehicle/titan-vermelha-2015-3.webp',
                    ],
                ],
                'venda' => [
                    'metodo_pagamento' => 'CREDIÁRIO PRÓPRIO',
                    'status'           => 'PENDENTE',
                    'data_venda'       => '2024-03-07',
                    'data_pagamento'   => '2024-03-07',
                    'numero_parcelas'  => 2,
                    'desconto'         => 0,
                    'total'            => 13000,
                ],
                'parcelas' => [
                    [
                        'data_vencimento' => '2024-04-07',
                        'valor'           => 6500,
                        'status'          => 'PENDENTE',
                    ],
                    [
                        'data_vencimento' => '2024-05-07',
                        'valor'           => 6500,
                        'status'          => 'PENDENTE',
                    ],
                ],
            ],
            [
                'veiculo' => [
                    'data_compra'    => '2024-03-03',
                    'preco_compra'   => 40500.00,
                    'preco_venda'    => 46000.00,
                    'modelo'         => 'Strada',
                    'ano_um'         => 2015,
                    'ano_dois'       => 2016,
                    'km'             => 26000,
                    'combustivel'    => 'FLEX',
                    'potencia_motor' => '1.0',
                    'transmissao'    => 'AUTOMÁTICA',
                    'cor'            => 'Vermelha',
                    'placa'          => 'AAA-0105',
                    'renavam'        => '000000105',
                    'chassi'         => '000000105',
                    'data_venda'     => '2024-03-07',
                    'descricao'      => 'Veículo em ótimo estado de conservação.',
                    'fotos'          => [
                        'photos/vehicle/strada-vermelha-2015-1.webp',
                        'photos/vehicle/strada-vermelha-2015-2.webp',
                        'photos/vehicle/strada-vermelha-2015-3.webp',
                    ],
                ],
                'venda' => [
                    'metodo_pagamento' => 'CREDIÁRIO PRÓPRIO',
                    'status'           => 'PAGO',
                    'data_venda'       => '2024-03-07',
                    'data_pagamento'   => '2024-03-07',
                    'numero_parcelas'  => 3,
                    'entrada'          => 40000,
                    'desconto'         => 0,
                    'total'            => 46000,
                ],
                'parcelas' => [
                    [
                        'data_vencimento'  => '2024-04-07',
                        'valor'            => 2000,
                        'status'           => 'PAGO',
                        'data_pagamento'   => '2024-04-07',
                        'valor_pagamento'  => 2000,
                        'metodo_pagamento' => 'CARTÃO DE CRÉDITO',
                    ],
                    [
                        'data_vencimento'  => '2024-05-07',
                        'valor'            => 2000,
                        'status'           => 'PAGO',
                        'data_pagamento'   => '2024-05-07',
                        'valor_pagamento'  => 2000,
                        'metodo_pagamento' => 'DINHEIRO',
                    ],
                    [
                        'data_vencimento'  => '2024-06-07',
                        'valor'            => 2000,
                        'status'           => 'PAGO',
                        'data_pagamento'   => '2024-06-07',
                        'valor_pagamento'  => 2000,
                        'metodo_pagamento' => 'TRANSFERÊNCIA',
                    ],
                ],
            ],
        ];

        foreach ($vendas as $dadosVenda) {
            $veiculo = $this->criarVeiculo($dadosVenda['veiculo'], $modelosVeiculos, $lojas, $compradores, $fornecedores, $acessorios, $extras, $tenant_id);
            $venda   = $this->criarVenda($dadosVenda['venda'], $veiculo, $vendedores, $clientes, $tenant_id);

            if (isset($dadosVenda['parcelas'])) {
                $this->criarParcelas($dadosVenda['parcelas'], $venda, $tenant_id);
            }
        }
    }

    private function criarVeiculo(array $dados, array $modelosVeiculos, $lojas, $compradores, $fornecedores, $acessorios, $extras, $tenant_id): Vehicle
    {
        $veiculo = Vehicle::create([
            'tenant_id'        => $tenant_id,
            'store_id'         => $lojas->random()->id,
            'buyer_id'         => $compradores->random()->id,
            'supplier_id'      => $fornecedores->random()->id,
            'purchase_date'    => $dados['data_compra'],
            'purchase_price'   => $dados['preco_compra'],
            'sale_price'       => $dados['preco_venda'],
            'vehicle_model_id' => $modelosVeiculos[$dados['modelo']] ?? null,
            'year_one'         => $dados['ano_um'],
            'year_two'         => $dados['ano_dois'],
            'km'               => $dados['km'],
            'fuel'             => $dados['combustivel'],
            'engine_power'     => $dados['potencia_motor'],
            'transmission'     => $dados['transmissao'],
            'color'            => $dados['cor'],
            'plate'            => $dados['placa'],
            'renavam'          => $dados['renavam'],
            'chassi'           => $dados['chassi'],
            'sold_date'        => $dados['data_venda'],
            'description'      => $dados['descricao'],
        ]);

        $veiculo->accessories()->attach($acessorios->random(rand(1, 20)));
        $veiculo->extras()->attach($extras->random(rand(1, 16)));

        foreach ($dados['fotos'] as $caminhoFoto) {
            Photo::withoutEvents(function () use ($veiculo, $caminhoFoto) {
                $veiculo->photos()->create([
                    'path'   => $caminhoFoto,
                    'public' => true,
                ]);
            });
        }

        return $veiculo;
    }

    private function criarVenda(array $dados, Vehicle $veiculo, $vendedores, $clientes, $tenant_id): Sale
    {
        return $veiculo->sale()->create([
            'tenant_id'           => $tenant_id,
            'store_id'            => $veiculo->store_id,
            'seller_id'           => $vendedores->random()->id,
            'client_id'           => $clientes->random()->id,
            'payment_method'      => $dados['metodo_pagamento'],
            'status'              => $dados['status'],
            'date_sale'           => $dados['data_venda'],
            'date_payment'        => $dados['data_pagamento'] ?? null,
            'number_installments' => $dados['numero_parcelas'] ?? null,
            'down_payment'        => $dados['entrada'] ?? null,
            'discount'            => $dados['desconto'] ?? 0,
            'total'               => $dados['total'],
        ]);
    }

    private function criarParcelas(array $parcelas, Sale $venda, $tenant_id): void
    {
        foreach ($parcelas as $dadosParcela) {
            $venda->paymentInstallments()->create(array_merge(
                $dadosParcela,
                [
                    'tenant_id' => $tenant_id,
                    'store_id'  => $venda->store_id,
                ]
            ));
        }
    }
}
