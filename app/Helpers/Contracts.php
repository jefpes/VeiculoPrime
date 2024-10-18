<?php

namespace App\Helpers;

use App\Models\Vehicle;
use App\Models\{Client, PaymentInstallments, Sale, Supplier, User};
use Illuminate\Support\Facades\{Auth, Storage};
use PhpOffice\PhpWord\TemplateProcessor;

class Contracts
{
    public static function setUserValues(TemplateProcessor $template): TemplateProcessor
    {
        // Substitui os placeholders com os dados do usuario
        $user = User::with('employee', 'employee.address')->find(Auth::user()->id);//@phpstan-ignore-line

        if ($user === null) {
            return $template;
        }

        if ($user->employee !== null) { //@phpstan-ignore-line
            $template->setValues([
                'usuario_nome'                     => $user->name ?? 'Valor não especificado',
                'usuario_nome_completo'            => $user->employee->name ?? 'Valor não especificado',
                'usuario_genero'                   => $user->employee->gender ?? 'Valor não especificado',
                'usuario_email'                    => $user->email ?? 'Valor não especificado',
                'usuario_telefone_1'               => $user->employee->phone_one ?? 'Valor não especificado',
                'usuario_telefone_2'               => $user->employee->phone_two ?? 'Valor não especificado',
                'usuario_salario'                  => number_format($user->employee->salary, 2, ',', '.'),
                'usuario_salario_extenso'          => Tools::spellNumber($user->employee->salary),
                'usuario_salario_dinheiro'         => Tools::spellMonetary($user->employee->salary),
                'usuario_rg'                       => $user->employee->rg ?? 'Valor não especificado',
                'usuario_cpf'                      => $user->employee->cpf ?? 'Valor não especificado',
                'usuario_data_nascimento'          => Tools::dateFormat($user->employee->birth_date),
                'usuario_data_nascimento_extenso'  => Tools::spellDate($user->employee->birth_date),
                'usuario_pai'                      => $user->employee->father ?? 'Valor não especificado',
                'usuario_mae'                      => $user->employee->mother ?? 'Valor não especificado',
                'usuario_estado_civil'             => $user->employee->marital_status ?? 'Valor não especificado',
                'usuario_conjuje'                  => $user->employee->spouse ?? 'Valor não especificado',
                'usuario_data_contratacao'         => Tools::dateFormat($user->employee->hiring_date),
                'usuario_data_contratacao_extenso' => Tools::spellDate($user->employee->hiring_date),
                'usuario_data_demissao'            => Tools::dateFormat($user->employee->resignation_date),
                'usuario_data_demissao_extenso'    => Tools::spellDate($user->employee->resignation_date),

                //Substitui os placeholders com os dados do endereco do usuario
                'usuario_endereco_cep'         => $user->employee->address->zip_code ?? 'Valor não especificado',
                'usuario_endereco_rua'         => $user->employee->address->street ?? 'Valor não especificado',
                'usuario_endereco_numero'      => $user->employee->address->number ?? 'Valor não especificado',
                'usuario_endereco_bairro'      => $user->employee->address->neighborhood ?? 'Valor não especificado',
                'usuario_endereco_cidade'      => $user->employee->address->city->name ?? 'Valor não especificado',
                'usuario_endereco_estado'      => $user->employee->address->state ?? 'Valor não especificado',
                'usuario_endereco_complemento' => $user->employee->address->complement ?? 'Valor não especificado',
            ]);
        }

        return $template;
    }

    public static function setClientValues(TemplateProcessor $template, ?int $clientId): TemplateProcessor
    {
        if ($clientId === null) {
            return $template;
        }

        $client = Client::with('address', 'address.city')->findOrFail($clientId);
        // Substitui os placeholders com os dados do cliente
        $template->setValues([
            'cliente_nome'                       => $client->name ?? 'Valor não especificado',
            'cliente_genero'                     => $client->gender ?? 'Valor não especificado',
            'cliente_tipo'                       => $client->client_type ?? 'Valor não especificado',
            'cliente_rg'                         => $client->rg ?? 'Valor não especificado',
            'cliente_cpf/cnpj'                   => $client->client_id ?? 'Valor não especificado',
            'cliente_estado_civil'               => $client->marital_status ?? 'Valor não especificado',
            'cliente_telefone_1'                 => $client->phone_one ?? 'Valor não especificado',
            'cliente_telefone_2'                 => $client->phone_two ?? 'Valor não especificado',
            'cliente_data_de_nascimento'         => Tools::dateFormat($client->birth_date), //@phpstan-ignore-line
            'cliente_data_de_nascimento_extenso' => Tools::spellDate($client->birth_date), //@phpstan-ignore-line
            'cliente_pai'                        => $client->father ?? 'Valor não especificado',
            'cliente_telefone_pai'               => $client->father_phone ?? 'Valor não especificado',
            'cliente_mae'                        => $client->mother ?? 'Valor não especificado',
            'cliente_telefone_mae'               => $client->mother_phone ?? 'Valor não especificado',
            'cliente_afiliado_1'                 => $client->affiliated_one ?? 'Valor não especificado',
            'cliente_telefone_afiliado_1'        => $client->affiliated_one_phone ?? 'Valor não especificado',
            'cliente_afiliado_2'                 => $client->affiliated_two ?? 'Valor não especificado',
            'cliente_telefone_afiliado_2'        => $client->affiliated_two_phone ?? 'Valor não especificado',
            'cliente_descricao'                  => $client->description ?? 'Valor não especificado',

            //Substitui os placeholders com os dados do endereco do cliente
            'cliente_endereco_cep'         => $client->address->zip_code ?? 'Valor não especificado',
            'cliente_endereco_rua'         => $client->address->street ?? 'Valor não especificado',
            'cliente_endereco_numero'      => $client->address->number ?? 'Valor não especificado',
            'cliente_endereco_bairro'      => $client->address->neighborhood ?? 'Valor não especificado',
            'cliente_endereco_cidade'      => $client->address->city->name ?? 'Valor não especificado',
            'cliente_endereco_estado'      => $client->address->state ?? 'Valor não especificado',
            'cliente_endereco_complemento' => $client->address->complement ?? 'Valor não especificado',
        ]);

        return $template;
    }

    public static function setVehicleValues(TemplateProcessor $template, ?int $vehicle_id): TemplateProcessor
    {
        if ($vehicle_id === null) {
            return $template;
        }

        $vehicle = Vehicle::with('model', 'model.type', 'model.brand')->findOrFail($vehicle_id);

        //Substitui os placeholders com os dados do veiculo
        $template->setValues([
            'data_compra'           => Tools::dateFormat($vehicle->purchase_date), //@phpstan-ignore-line
            'data_compra_extenso'   => Tools::spellDate($vehicle->purchase_date), //@phpstan-ignore-line
            'preco_fipe'            => number_format($vehicle->fipe_price, 2, ',', '.'), //@phpstan-ignore-line
            'preco_fipe_extenso'    => Tools::spellNumber($vehicle->fipe_price), //@phpstan-ignore-line
            'preco_fipe_dinheiro'   => Tools::spellMonetary($vehicle->fipe_price), //@phpstan-ignore-line
            'preco_compra'          => number_format($vehicle->purchase_price, 2, ',', '.'), //@phpstan-ignore-line
            'preco_compra_extenso'  => Tools::spellNumber($vehicle->purchase_price), //@phpstan-ignore-line
            'preco_compra_dinheiro' => Tools::spellMonetary($vehicle->purchase_price), //@phpstan-ignore-line
            'preco_venda'           => number_format($vehicle->promotional_price ?? $vehicle->sale_price, 2, ',', '.'), //@phpstan-ignore-line
            'preco_venda_extenso'   => Tools::spellNumber($vehicle->promotional_price ?? $vehicle->sale_price), //@phpstan-ignore-line
            'preco_venda_dinheiro'  => Tools::spellMonetary($vehicle->promotional_price ?? $vehicle->sale_price), //@phpstan-ignore-line
            'modelo'                => $vehicle->model->name ?? 'Valor não especificado',
            'tipo'                  => $vehicle->model->type->name ?? 'Valor não especificado',
            'marca'                 => $vehicle->model->brand->name ?? 'Valor não especificado',
            'ano_um'                => $vehicle->year_one ?? 'Valor não especificado',
            'ano_dois'              => $vehicle->year_two ?? 'Valor não especificado',
            'km'                    => number_format($vehicle->km, 0, '', '.'), //@phpstan-ignore-line
            'km_extenso'            => Tools::spellNumber($vehicle->km), //@phpstan-ignore-line
            'combustivel'           => $vehicle->fuel ?? 'Valor não especificado',
            'motor_potencia'        => $vehicle->engine_power ?? 'Valor não especificado',
            'direcao'               => $vehicle->steering ?? 'Valor não especificado',
            'transmissao'           => $vehicle->transmission ?? 'Valor não especificado',
            'portas'                => $vehicle->doors ?? 'Valor não especificado',
            'portas_extenso'        => Tools::spellNumber($vehicle->doors), //@phpstan-ignore-line
            'lugares'               => $vehicle->seats ?? 'Valor não especificado',
            'lugares_extenso'       => Tools::spellNumber($vehicle->seats), //@phpstan-ignore-line
            'tracao'                => $vehicle->traction ?? 'Valor não especificado',
            'cor'                   => $vehicle->color ?? 'Valor não especificado',
            'placa'                 => $vehicle->plate ?? 'Valor não especificado',
            'chassi'                => $vehicle->chassi ?? 'Valor não especificado',
            'renavam'               => $vehicle->renavam ?? 'Valor não especificado',
            'descricao'             => $vehicle->description ?? 'Valor não especificado',
            'anotacao'              => $vehicle->annotation ?? 'Valor não especificado',
        ]);

        return $template;
    }

    public static function setSupplierValues(TemplateProcessor $template, ?int $supplier_id): TemplateProcessor
    {
        if ($supplier_id === null) {
            return $template;
        }

        $supplier = Supplier::with('address', 'address.city')->findOrFail($supplier_id);

        //Substitui os placeholders com os dados do fornecedor
        $template->setValues([
            'fornecedor_nome'                       => $supplier->name ?? 'Valor não especificado',
            'fornecedor_genero'                     => $supplier->gender ?? 'Valor não especificado',
            'fornecedor_tipo'                       => $supplier->supplier_type ?? 'Valor não especificado',
            'fornecedor_rg'                         => $supplier->rg ?? 'Valor não especificado',
            'fornecedor_cpf/cnpj'                   => $supplier->supplier_id ?? 'Valor não especificado',
            'fornecedor_estado_civil'               => $supplier->marital_status ?? 'Valor não especificado',
            'fornecedor_telefone_1'                 => $supplier->phone_one ?? 'Valor não especificado',
            'fornecedor_telefone_2'                 => $supplier->phone_two ?? 'Valor não especificado',
            'fornecedor_data_de_nascimento'         => Tools::dateFormat($supplier->birth_date), //@phpstan-ignore-line
            'fornecedor_data_de_nascimento_extenso' => Tools::spellDate($supplier->birth_date), //@phpstan-ignore-line
            'fornecedor_pai'                        => $supplier->father ?? 'Valor não especificado',
            'fornecedor_telefone_pai'               => $supplier->father_phone ?? 'Valor não especificado',
            'fornecedor_mae'                        => $supplier->mother ?? 'Valor não especificado',
            'fornecedor_telefone_mae'               => $supplier->mother_phone ?? 'Valor não especificado',
            'fornecedor_afiliado_1'                 => $supplier->affiliated_one ?? 'Valor não especificado',
            'fornecedor_telefone_afiliado_1'        => $supplier->affiliated_one_phone ?? 'Valor não especificado',
            'fornecedor_afiliado_2'                 => $supplier->affiliated_two ?? 'Valor não especificado',
            'fornecedor_telefone_afiliado_2'        => $supplier->affiliated_two_phone ?? 'Valor não especificado',
            'fornecedor_descricao'                  => $supplier->description ?? 'Valor não especificado',

            //Substitui os placeholders com os dados do endereco do fornecedor
            'fornecedor_endereco_cep'         => $supplier->address->zip_code ?? 'Valor não especificado',
            'fornecedor_endereco_rua'         => $supplier->address->street ?? 'Valor não especificado',
            'fornecedor_endereco_numero'      => $supplier->address->number ?? 'Valor não especificado',
            'fornecedor_endereco_bairro'      => $supplier->address->neighborhood ?? 'Valor não especificado',
            'fornecedor_endereco_cidade'      => $supplier->address->city->name ?? 'Valor não especificado',
            'fornecedor_endereco_estado'      => $supplier->address->state ?? 'Valor não especificado',
            'fornecedor_endereco_complemento' => $supplier->address->complement ?? 'Valor não especificado',
        ]);

        return $template;
    }

    public static function setSaleValues(TemplateProcessor $template, ?int $sale_id): TemplateProcessor
    {
        if ($sale_id === null) {
            return $template;
        }

        $sale = Sale::findOrFail($sale_id);//@phpstan-ignore-line

        //Substitui os placeholders com os dados da venda
        $template->setValues([
            'metodo_de_pagamento'       => $sale->payment_method ?? 'Valor não especificado',
            'status'                    => $sale->status ?? 'Valor não especificado',
            'data_venda'                => Tools::dateFormat($sale->date_sale),
            'data_venda_extenso'        => Tools::spellDate($sale->date_sale),
            'data_pagamento'            => Tools::dateFormat($sale->date_payment),
            'data_pagamento_extenso'    => Tools::spellDate($sale->date_payment),
            'desconto'                  => number_format($sale->discount, 2, ',', '.'),
            'desconto_extenso'          => Tools::spellNumber($sale->discount),
            'desconto_dinheiro'         => Tools::spellMonetary($sale->discount),
            'acrescimo'                 => number_format($sale->surcharge, 2, ',', '.'),
            'acrescimo_extenso'         => Tools::spellNumber($sale->surcharge),
            'acrescimo_dinheiro'        => Tools::spellMonetary($sale->surcharge),
            'entrada'                   => number_format($sale->down_payment, 2, ',', '.'),
            'entrada_extenso'           => Tools::spellNumber($sale->down_payment),
            'entrada_dinheiro'          => Tools::spellMonetary($sale->down_payment),
            'numero_parcelas'           => $sale->number_installments,
            'numero_parcelas_extenso'   => Tools::spellNumber($sale->number_installments),
            'reembolso'                 => number_format($sale->reimbursement, 2, ',', '.'),
            'reembolso_extenso'         => Tools::spellNumber($sale->reimbursement),
            'reembolso_dinheiro'        => Tools::spellMonetary($sale->reimbursement),
            'data_cancelamento'         => Tools::dateFormat($sale->date_cancel),
            'data_cancelamento_extenso' => Tools::spellDate($sale->date_cancel),
            'total'                     => number_format($sale->total, 2, ',', '.'),
            'total_extenso'             => Tools::spellNumber($sale->total),
            'total_dinheiro'            => Tools::spellMonetary($sale->total),
        ]);

        return $template;
    }

    public static function setInstallmentValues(TemplateProcessor $template, ?int $installment_id): TemplateProcessor
    {
        if ($installment_id === null) {
            return $template;
        }

        $installment = PaymentInstallments::findOrFail($installment_id); //@phpstan-ignore-line

        //Substitui os placeholders com os dados da parcela
        $template->setValues([
            "parcela_data_vencimento"          => Tools::dateFormat($installment->due_date),
            "parcela_data_vencimento_extenso"  => Tools::spellDate($installment->due_date),
            "parcela_valor"                    => number_format($installment->value, 2, ',', '.'),
            "parcela_valor_extenso"            => Tools::spellNumber($installment->value),
            "parcela_valor_dinheiro"           => Tools::spellMonetary($installment->value),
            "parcela_status"                   => $installment->status ?? 'Valor não especificado',
            "parcela_data_pagamento"           => Tools::dateFormat($installment->payment_date),
            "parcela_data_pagamento_extenso"   => Tools::spellDate($installment->payment_date),
            "parcela_valor_pagamento"          => number_format($installment->payment_value, 2, ',', '.'),
            "parcela_valor_pagamento_extenso"  => Tools::spellNumber($installment->payment_value),
            "parcela_valor_pagamento_dinheiro" => Tools::spellMonetary($installment->payment_value),
            "parcela_metodo_pagamento"         => $installment->payment_method ?? 'Valor não especificado',
            "parcela_desconto"                 => number_format($installment->discount, 2, ',', '.'),
            "parcela_desconto_extenso"         => Tools::spellNumber($installment->discount),
            "parcela_desconto_dinheiro"        => Tools::spellMonetary($installment->discount),
            "parcela_acrescimo"                => number_format($installment->surcharge, 2, ',', '.'),
            "parcela_acrescimo_extenso"        => Tools::spellNumber($installment->surcharge),
            "parcela_acrescimo_dinheiro"       => Tools::spellMonetary($installment->surcharge),
        ]);

        return $template;
    }

    public static function setInstallmentsValues(TemplateProcessor $template, ?int $sale_id): TemplateProcessor
    {
        if ($sale_id === null) {
            return $template;
        }

        $sale = Sale::with('paymentInstallments')->findOrFail($sale_id);

        //Substitui os placeholders com os dados das parcelas
        if ($sale->number_installments > 1) { //@phpstan-ignore-line
            for ($i = 0; $i < $sale->number_installments; $i++) {
                $template->setValues([
                    "parcela_" . ($i + 1) . "_data_vencimento"          => Tools::dateFormat($sale->paymentInstallments[$i]->due_date), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_data_vencimento_extenso"  => Tools::spellDate($sale->paymentInstallments[$i]->due_date), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor"                    => number_format($sale->paymentInstallments[$i]->value, 2, ',', '.'), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_extenso"            => Tools::spellNumber($sale->paymentInstallments[$i]->value), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_dinheiro"           => Tools::spellMonetary($sale->paymentInstallments[$i]->value), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_status"                   => $sale->paymentInstallments[$i]->status ?? 'Valor não especificado',
                    "parcela_" . ($i + 1) . "_data_pagamento"           => Tools::dateFormat($sale->paymentInstallments[$i]->payment_date), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_data_pagamento_extenso"   => Tools::spellDate($sale->paymentInstallments[$i]->payment_date), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_pagamento"          => number_format($sale->paymentInstallments[$i]->payment_value, 2, ',', '.'), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_pagamento_extenso"  => Tools::spellNumber($sale->paymentInstallments[$i]->payment_value), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_pagamento_dinheiro" => Tools::spellMonetary($sale->paymentInstallments[$i]->payment_value), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_metodo_pagamento"         => $sale->paymentInstallments[$i]->payment_method, //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_desconto"                 => number_format($sale->paymentInstallments[$i]->discount, 2, ',', '.'), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_desconto_extenso"         => Tools::spellNumber($sale->paymentInstallments[$i]->discount), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_desconto_dinheiro"        => Tools::spellMonetary($sale->paymentInstallments[$i]->discount), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_acrescimo"                => number_format($sale->paymentInstallments[$i]->surcharge, 2, ',', '.'), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_acrescimo_extenso"        => Tools::spellNumber($sale->paymentInstallments[$i]->surcharge), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_acrescimo_dinheiro"       => Tools::spellMonetary($sale->paymentInstallments[$i]->surcharge), //@phpstan-ignore-line
                ]);
            }
        }

        return $template;
    }

    public static function generateSaleContract(TemplateProcessor $template, Sale $sale): string
    {
        // Substitui os placeholders com os dados do usuario
        $user = User::with('employee', 'employee.address')->find(Auth::user()->id); //@phpstan-ignore-line

        if ($user->employee !== null) { //@phpstan-ignore-line
            $template->setValue('usuario_nome', $user->name); //@phpstan-ignore-line
            $template->setValue('usuario_nome_completo', $user->employee->name);
            $template->setValue('usuario_genero', $user->employee->gender);
            $template->setValue('usuario_email', $user->email); //@phpstan-ignore-line
            $template->setValue('usuario_telefone_1', $user->employee->phone_one);
            $template->setValue('usuario_telefone_2', $user->employee->phone_two);
            $template->setValue('usuario_salario', number_format($user->employee->salary, 2, ',', '.'));
            $template->setValue('usuario_salario_extenso', Tools::spellNumber($user->employee->salary));
            $template->setValue('usuario_salario_dinheiro', Tools::spellMonetary($user->employee->salary));
            $template->setValue('usuario_rg', $user->employee->rg);
            $template->setValue('usuario_cpf', $user->employee->cpf);
            $template->setValue('usuario_data_nascimento', Tools::dateFormat($user->employee->birth_date));
            $template->setValue('usuario_data_nascimento_extenso', Tools::spellDate($user->employee->birth_date));
            $template->setValue('usuario_pai', $user->employee->father);
            $template->setValue('usuario_mae', $user->employee->mother);
            $template->setValue('usuario_estado_civil', $user->employee->marital_status);
            $template->setValue('usuario_conjuje', $user->employee->spouse);
            $template->setValue('usuario_data_contratacao', Tools::dateFormat($user->employee->hiring_date));
            $template->setValue('usuario_data_contratacao_extenso', Tools::spellDate($user->employee->hiring_date));
            $template->setValue('usuario_data_demissao', Tools::dateFormat($user->employee->resignation_date));
            $template->setValue('usuario_data_demissao_extenso', Tools::spellDate($user->employee->resignation_date));

            //Substitui os placeholders com os dados do endereco do usuario
            $template->setValue('usuario_endereco_cep', $user->employee->address->zip_code);
            $template->setValue('usuario_endereco_rua', $user->employee->address->street);
            $template->setValue('usuario_endereco_numero', $user->employee->address->number);
            $template->setValue('usuario_endereco_bairro', $user->employee->address->neighborhood);
            $template->setValue('usuario_endereco_cidade', $user->employee->address->city->name);
            $template->setValue('usuario_endereco_estado', $user->employee->address->state);
            $template->setValue('usuario_endereco_complemento', $user->employee->address->complement);
        }

        // Substitui os placeholders com os dados do cliente
        $template->setValue('cliente_nome', $sale->client->name); //@phpstan-ignore-line
        $template->setValue('cliente_genero', $sale->client->gender); //@phpstan-ignore-line
        $template->setValue('cliente_tipo', $sale->client->client_type); //@phpstan-ignore-line
        $template->setValue('cliente_rg', $sale->client->rg); //@phpstan-ignore-line
        $template->setValue('cliente_cpf/cnpj', $sale->client->client_id); //@phpstan-ignore-line
        $template->setValue('cliente_estado_civil', $sale->client->marital_status); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_1', $sale->client->phone_one); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_2', $sale->client->phone_two); //@phpstan-ignore-line
        $template->setValue('cliente_data_de_nascimento', Tools::dateFormat($sale->client->birth_date)); //@phpstan-ignore-line
        $template->setValue('cliente_data_de_nascimento_extenso', Tools::spellDate($sale->client->birth_date)); //@phpstan-ignore-line
        $template->setValue('cliente_pai', $sale->client->father); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_pai', $sale->client->father_phone); //@phpstan-ignore-line
        $template->setValue('cliente_mae', $sale->client->mother); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_mae', $sale->client->mother_phone); //@phpstan-ignore-line
        $template->setValue('cliente_afiliado_1', $sale->client->affiliated_one); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_afiliado_1', $sale->client->affiliated_one_phone); //@phpstan-ignore-line
        $template->setValue('cliente_afiliado_2', $sale->client->affiliated_two); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_afiliado_2', $sale->client->affiliated_two_phone); //@phpstan-ignore-line
        $template->setValue('cliente_descricao', $sale->client->description); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do endereco do cliente
        $template->setValue('cliente_endereco_cep', $sale->client->address->zip_code); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_rua', $sale->client->address->street); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_numero', $sale->client->address->number); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_bairro', $sale->client->address->neighborhood); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_cidade', $sale->client->address->city->name); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_estado', $sale->client->address->state); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_complemento', $sale->client->address->complement); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do veiculo
        $template->setValue('data_compra', Tools::dateFormat($sale->vehicle->purchase_date)); //@phpstan-ignore-line
        $template->setValue('data_compra_extenso', Tools::spellDate($sale->vehicle->purchase_date)); //@phpstan-ignore-line
        $template->setValue('preco_fipe', number_format($sale->vehicle->fipe_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('preco_fipe_extenso', Tools::spellNumber($sale->vehicle->fipe_price)); //@phpstan-ignore-line
        $template->setValue('preco_fipe_dinheiro', Tools::spellMonetary($sale->vehicle->fipe_price)); //@phpstan-ignore-line
        $template->setValue('preco_compra', number_format($sale->vehicle->purchase_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('preco_compra_extenso', Tools::spellNumber($sale->vehicle->purchase_price)); //@phpstan-ignore-line
        $template->setValue('preco_compra_dinheiro', Tools::spellMonetary($sale->vehicle->purchase_price)); //@phpstan-ignore-line
        $template->setValue('preco_venda', number_format($sale->vehicle->promotional_price ?? $sale->vehicle->sale_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('preco_venda_extenso', Tools::spellNumber($sale->vehicle->promotional_price ?? $sale->vehicle->sale_price)); //@phpstan-ignore-line
        $template->setValue('preco_venda_dinheiro', Tools::spellMonetary($sale->vehicle->promotional_price ?? $sale->vehicle->sale_price)); //@phpstan-ignore-line
        $template->setValue('modelo', $sale->vehicle->model->name); //@phpstan-ignore-line
        $template->setValue('tipo', $sale->vehicle->model->type->name); //@phpstan-ignore-line
        $template->setValue('marca', $sale->vehicle->model->brand->name); //@phpstan-ignore-line
        $template->setValue('ano_um', $sale->vehicle->year_one); //@phpstan-ignore-line
        $template->setValue('ano_dois', $sale->vehicle->year_two); //@phpstan-ignore-line
        $template->setValue('km', number_format($sale->vehicle->km, 0, '', '.')); //@phpstan-ignore-line
        $template->setValue('km_extenso', Tools::spellNumber($sale->vehicle->km)); //@phpstan-ignore-line
        $template->setValue('combustivel', $sale->vehicle->fuel); //@phpstan-ignore-line
        $template->setValue('motor_potencia', $sale->vehicle->engine_power); //@phpstan-ignore-line
        $template->setValue('direcao', $sale->vehicle->steering); //@phpstan-ignore-line
        $template->setValue('transmissao', $sale->vehicle->transmission); //@phpstan-ignore-line
        $template->setValue('portas', $sale->vehicle->doors); //@phpstan-ignore-line
        $template->setValue('portas_extenso', Tools::spellNumber($sale->vehicle->doors)); //@phpstan-ignore-line
        $template->setValue('lugares', $sale->vehicle->seats); //@phpstan-ignore-line
        $template->setValue('lugares_extenso', Tools::spellNumber($sale->vehicle->seats)); //@phpstan-ignore-line
        $template->setValue('tracao', $sale->vehicle->traction); //@phpstan-ignore-line
        $template->setValue('cor', $sale->vehicle->color); //@phpstan-ignore-line
        $template->setValue('placa', $sale->vehicle->plate); //@phpstan-ignore-line
        $template->setValue('chassi', $sale->vehicle->chassi); //@phpstan-ignore-line
        $template->setValue('renavam', $sale->vehicle->renavam); //@phpstan-ignore-line
        $template->setValue('descricao', $sale->vehicle->description); //@phpstan-ignore-line
        $template->setValue('anotacao', $sale->vehicle->annotation); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do fornecedor
        if ($sale->supplier !== null) { //@phpstan-ignore-line
            $template->setValue('fornecedor_nome', $sale->supplier->name);
            $template->setValue('fornecedor_genero', $sale->supplier->gender);
            $template->setValue('fornecedor_tipo', $sale->supplier->client_type);
            $template->setValue('fornecedor_rg', $sale->supplier->rg);
            $template->setValue('fornecedor_cpf/cnpj', $sale->supplier->client_id);
            $template->setValue('fornecedor_estado_civil', $sale->supplier->marital_status);
            $template->setValue('fornecedor_telefone_1', $sale->supplier->phone_one);
            $template->setValue('fornecedor_telefone_2', $sale->supplier->phone_two);
            $template->setValue('fornecedor_data_de_nascimento', Tools::dateFormat($sale->supplier->birth_date));
            $template->setValue('fornecedor_data_de_nascimento_extenso', Tools::spellDate($sale->supplier->birth_date));
            $template->setValue('fornecedor_pai', $sale->supplier->father);
            $template->setValue('fornecedor_telefone_pai', $sale->supplier->father_phone);
            $template->setValue('fornecedor_mae', $sale->supplier->mother);
            $template->setValue('fornecedor_telefone_mae', $sale->supplier->mother_phone);
            $template->setValue('fornecedor_afiliado_1', $sale->supplier->affiliated_one);
            $template->setValue('fornecedor_telefone_afiliado_1', $sale->supplier->affiliated_one_phone);
            $template->setValue('fornecedor_afiliado_2', $sale->supplier->affiliated_two);
            $template->setValue('fornecedor_telefone_afiliado_2', $sale->supplier->affiliated_two_phone);
            $template->setValue('fornecedor_descricao', $sale->supplier->description);

            //Substitui os placeholders com os dados do endereco do fornecedor
            $template->setValue('fornecedor_endereco_cep', $sale->supplier->address->zip_code);
            $template->setValue('fornecedor_endereco_rua', $sale->supplier->address->street);
            $template->setValue('fornecedor_endereco_numero', $sale->supplier->address->number);
            $template->setValue('fornecedor_endereco_bairro', $sale->supplier->address->neighborhood);
            $template->setValue('fornecedor_endereco_cidade', $sale->supplier->address->city->name);
            $template->setValue('fornecedor_endereco_estado', $sale->supplier->address->state);
            $template->setValue('fornecedor_endereco_complemento', $sale->supplier->address->complement);
        }

        //Substitui os placeholders com os dados da venda
        $template->setValue('metodo_de_pagamento', $sale->payment_method); //@phpstan-ignore-line
        $template->setValue('status', $sale->status); //@phpstan-ignore-line
        $template->setValue('data_venda', Tools::dateFormat($sale->date_sale)); //@phpstan-ignore-line
        $template->setValue('data_venda_extenso', Tools::spellDate($sale->date_sale)); //@phpstan-ignore-line
        $template->setValue('data_pagamento', Tools::dateFormat($sale->date_payment)); //@phpstan-ignore-line
        $template->setValue('data_pagamento_extenso', Tools::spellDate($sale->date_payment)); //@phpstan-ignore-line
        $template->setValue('desconto', $sale->discount); //@phpstan-ignore-line
        $template->setValue('desconto_extenso', Tools::spellNumber($sale->discount)); //@phpstan-ignore-line
        $template->setValue('desconto_dinheiro', Tools::spellMonetary($sale->discount)); //@phpstan-ignore-line
        $template->setValue('acrescimo', $sale->surcharge); //@phpstan-ignore-line
        $template->setValue('acrescimo_extenso', Tools::spellNumber($sale->surcharge)); //@phpstan-ignore-line
        $template->setValue('acrescimo_dinheiro', Tools::spellMonetary($sale->surcharge)); //@phpstan-ignore-line
        $template->setValue('entrada', number_format($sale->down_payment, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('entrada_extenso', Tools::spellNumber($sale->down_payment)); //@phpstan-ignore-line
        $template->setValue('entrada_dinheiro', Tools::spellMonetary($sale->down_payment)); //@phpstan-ignore-line
        $template->setValue('numero_parcelas', $sale->number_installments); //@phpstan-ignore-line
        $template->setValue('numero_parcelas_extenso', Tools::spellNumber($sale->number_installments)); //@phpstan-ignore-line
        $template->setValue('reembolso', number_format($sale->reimbursement, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('reembolso_extenso', Tools::spellNumber($sale->reimbursement)); //@phpstan-ignore-line
        $template->setValue('reembolso_dinheiro', Tools::spellMonetary($sale->reimbursement)); //@phpstan-ignore-line
        $template->setValue('data_cancelamento', Tools::dateFormat($sale->date_cancel)); //@phpstan-ignore-line
        $template->setValue('data_cancelamento_extenso', Tools::spellDate($sale->date_cancel)); //@phpstan-ignore-line
        $template->setValue('total', number_format($sale->total, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('total_extenso', Tools::spellNumber($sale->total)); //@phpstan-ignore-line
        $template->setValue('total_dinheiro', Tools::spellMonetary($sale->total)); //@phpstan-ignore-line

        if ($sale->number_installments > 1) { //@phpstan-ignore-line
            for ($i = 0; $i < $sale->number_installments; $i++) {
                $template->setValues([
                    "parcela_" . ($i + 1) . "_data_vencimento"          => Tools::dateFormat($sale->paymentInstallments[$i]->due_date), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_data_vencimento_extenso"  => Tools::spellDate($sale->paymentInstallments[$i]->due_date), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor"                    => number_format($sale->paymentInstallments[$i]->value, 2, ',', '.'), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_extenso"            => Tools::spellNumber($sale->paymentInstallments[$i]->value), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_dinheiro"           => Tools::spellMonetary($sale->paymentInstallments[$i]->value), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_status"                   => $sale->paymentInstallments[$i]->status, //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_data_pagamento"           => Tools::dateFormat($sale->paymentInstallments[$i]->payment_date), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_data_pagamento_extenso"   => Tools::spellDate($sale->paymentInstallments[$i]->payment_date), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_pagamento"          => number_format($sale->paymentInstallments[$i]->payment_value, 2, ',', '.'), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_pagamento_extenso"  => Tools::spellNumber($sale->paymentInstallments[$i]->payment_value), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_valor_pagamento_dinheiro" => Tools::spellMonetary($sale->paymentInstallments[$i]->payment_value), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_metodo_pagamento"         => $sale->paymentInstallments[$i]->payment_method, //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_desconto"                 => number_format($sale->paymentInstallments[$i]->discount, 2, ',', '.'), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_desconto_extenso"         => Tools::spellNumber($sale->paymentInstallments[$i]->discount), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_desconto_dinheiro"        => Tools::spellMonetary($sale->paymentInstallments[$i]->discount), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_acrescimo"                => number_format($sale->paymentInstallments[$i]->surcharge, 2, ',', '.'), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_acrescimo_extenso"        => Tools::spellNumber($sale->paymentInstallments[$i]->surcharge), //@phpstan-ignore-line
                    "parcela_" . ($i + 1) . "_acrescimo_dinheiro"       => Tools::spellMonetary($sale->paymentInstallments[$i]->surcharge), //@phpstan-ignore-line
                ]);
            }
        }

        // Salva o contrato preenchido
        file_exists(public_path('storage\contracts')) ?: Storage::makeDirectory('public\contracts');
        $caminhoContratoPreenchido = "storage/contracts/Contrato - {$sale->client->name}.docx"; //@phpstan-ignore-line
        $template->saveAs($caminhoContratoPreenchido);

        return $caminhoContratoPreenchido;
    }

    public static function generateReceiptContract(TemplateProcessor $template, PaymentInstallments $installment): string
    {
        // Substitui os placeholders com os dados do usuario
        self::setUserValues($template);

        // Substitui os placeholders com os dados do cliente
        self::setClientValues($template, $installment->sale->client->id ?? null);

        //Substitui os placeholders com os dados do veiculo
        self::setVehicleValues($template, $installment->sale->vehicle->id ?? null);

        //Substitui os placeholders com os dados do fornecedor
        self::setSupplierValues($template, $installment->sale->vehicle->supplier->id ?? null);

        //Substitui os placeholders com os dados da venda
        self::setSaleValues($template, $installment->sale->id ?? null);

        //Substitui os placeholders com os dados da parcela
        self::setInstallmentValues($template, $installment->id ?? null);

        // Salva o contrato preenchido
        file_exists(public_path('storage\contracts')) ?: Storage::makeDirectory('public\contracts');
        $caminhoContratoPreenchido = "storage/contracts/Recibo - {$installment->sale->client->name}.docx"; //@phpstan-ignore-line
        $template->saveAs($caminhoContratoPreenchido);

        return $caminhoContratoPreenchido;
    }
}
