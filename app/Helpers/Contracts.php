<?php

namespace App\Helpers;

use App\Models\Vehicle;
use App\Models\{Client, PaymentInstallments, Sale, Supplier, User, VehicleExpense};
use Illuminate\Support\Facades\{Auth, Storage};
use PhpOffice\PhpWord\TemplateProcessor;

class Contracts
{
    public static function setUserValues(TemplateProcessor $template, ?int $user_id = null): void
    {
        // Substitui os placeholders com os dados do usuario
        if ($user_id !== null) {
            $user = User::with('employee', 'employee.address')->find($user_id);
        }

        if ($user_id === null) {
            $user = User::with('employee', 'employee.address')->find(Auth::user()->id); //@phpstan-ignore-line
        }

        if ($user === null) {
            return;
        }

        if ($user->employee !== null) {
            $template->setValues([
                'ucv_nome'                     => $user->name ?? 'Valor não especificado',
                'ucv_nome_completo'            => $user->employee->name ?? 'Valor não especificado',
                'ucv_genero'                   => $user->employee->gender ?? 'Valor não especificado',
                'ucv_email'                    => $user->email ?? 'Valor não especificado',
                'ucv_telefone_1'               => $user->employee->phone_one ?? 'Valor não especificado',
                'ucv_telefone_2'               => $user->employee->phone_two ?? 'Valor não especificado',
                'ucv_salario'                  => number_format($user->employee->salary, 2, ',', '.'),
                'ucv_salario_extenso'          => Tools::spellNumber($user->employee->salary),
                'ucv_salario_dinheiro'         => Tools::spellMonetary($user->employee->salary),
                'ucv_rg'                       => $user->employee->rg ?? 'Valor não especificado',
                'ucv_cpf'                      => $user->employee->cpf ?? 'Valor não especificado',
                'ucv_data_nascimento'          => Tools::dateFormat($user->employee->birth_date),
                'ucv_data_nascimento_extenso'  => Tools::spellDate($user->employee->birth_date),
                'ucv_pai'                      => $user->employee->father ?? 'Valor não especificado',
                'ucv_mae'                      => $user->employee->mother ?? 'Valor não especificado',
                'ucv_estado_civil'             => $user->employee->marital_status ?? 'Valor não especificado',
                'ucv_conjuje'                  => $user->employee->spouse ?? 'Valor não especificado',
                'ucv_data_contratacao'         => Tools::dateFormat($user->employee->admission_date),
                'ucv_data_contratacao_extenso' => Tools::spellDate($user->employee->admission_date),
                'ucv_data_demissao'            => Tools::dateFormat($user->employee->resignation_date),
                'ucv_data_demissao_extenso'    => Tools::spellDate($user->employee->resignation_date),
            ]);

            //Substitui os placeholders com os dados do endereco do usuario
            $template->setValues([
                'ucv_endereco_cep'         => $user->employee->address->zip_code ?? 'Valor não especificado',
                'ucv_endereco_rua'         => $user->employee->address->street ?? 'Valor não especificado',
                'ucv_endereco_numero'      => $user->employee->address->number ?? 'Valor não especificado',
                'ucv_endereco_bairro'      => $user->employee->address->neighborhood ?? 'Valor não especificado',
                'ucv_endereco_cidade'      => $user->employee->address->city->name ?? 'Valor não especificado',
                'ucv_endereco_estado'      => $user->employee->address->state ?? 'Valor não especificado',
                'ucv_endereco_complemento' => $user->employee->address->complement ?? 'Valor não especificado',
            ]);
        }
    }

    public static function setClientValues(TemplateProcessor $template, ?int $clientId): void
    {
        if ($clientId === null) {
            return;
        }

        $client = Client::with('address', 'address.city')->find($clientId);

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
            'cliente_data_de_nascimento'         => Tools::dateFormat($client->birth_date),
            'cliente_data_de_nascimento_extenso' => Tools::spellDate($client->birth_date),
            'cliente_pai'                        => $client->father ?? 'Valor não especificado',
            'cliente_telefone_pai'               => $client->father_phone ?? 'Valor não especificado',
            'cliente_mae'                        => $client->mother ?? 'Valor não especificado',
            'cliente_telefone_mae'               => $client->mother_phone ?? 'Valor não especificado',
            'cliente_afiliado_1'                 => $client->affiliated_one ?? 'Valor não especificado',
            'cliente_telefone_afiliado_1'        => $client->affiliated_one_phone ?? 'Valor não especificado',
            'cliente_afiliado_2'                 => $client->affiliated_two ?? 'Valor não especificado',
            'cliente_telefone_afiliado_2'        => $client->affiliated_two_phone ?? 'Valor não especificado',
            'cliente_descricao'                  => $client->description ?? 'Valor não especificado',
        ]);

        //Substitui os placeholders com os dados do endereco do cliente
        $template->setValues([
            'cliente_endereco_cep'         => $client->address->zip_code ?? 'Valor não especificado',
            'cliente_endereco_rua'         => $client->address->street ?? 'Valor não especificado',
            'cliente_endereco_numero'      => $client->address->number ?? 'Valor não especificado',
            'cliente_endereco_bairro'      => $client->address->neighborhood ?? 'Valor não especificado',
            'cliente_endereco_cidade'      => $client->address->city->name ?? 'Valor não especificado',
            'cliente_endereco_estado'      => $client->address->state ?? 'Valor não especificado',
            'cliente_endereco_complemento' => $client->address->complement ?? 'Valor não especificado',
        ]);
    }

    public static function setVehicleValues(TemplateProcessor $template, ?int $vehicle_id): void
    {
        if ($vehicle_id === null) {
            return;
        }

        $vehicle = Vehicle::with('model', 'model.type', 'model.brand')->find($vehicle_id);

        //Substitui os placeholders com os dados do veiculo
        $template->setValues([
            'data_compra'           => Tools::dateFormat($vehicle->purchase_date),
            'data_compra_extenso'   => Tools::spellDate($vehicle->purchase_date),
            'preco_fipe'            => number_format($vehicle->fipe_price, 2, ',', '.'),
            'preco_fipe_extenso'    => Tools::spellNumber($vehicle->fipe_price),
            'preco_fipe_dinheiro'   => Tools::spellMonetary($vehicle->fipe_price),
            'preco_compra'          => number_format($vehicle->purchase_price, 2, ',', '.'),
            'preco_compra_extenso'  => Tools::spellNumber($vehicle->purchase_price),
            'preco_compra_dinheiro' => Tools::spellMonetary($vehicle->purchase_price),
            'preco_venda'           => number_format($vehicle->promotional_price ?? $vehicle->sale_price, 2, ',', '.'),
            'preco_venda_extenso'   => Tools::spellNumber($vehicle->promotional_price ?? $vehicle->sale_price),
            'preco_venda_dinheiro'  => Tools::spellMonetary($vehicle->promotional_price ?? $vehicle->sale_price),
            'modelo'                => $vehicle->model->name ?? 'Valor não especificado',
            'tipo'                  => $vehicle->model->type->name ?? 'Valor não especificado',
            'marca'                 => $vehicle->model->brand->name ?? 'Valor não especificado',
            'ano_um'                => $vehicle->year_one ?? 'Valor não especificado',
            'ano_dois'              => $vehicle->year_two ?? 'Valor não especificado',
            'km'                    => number_format($vehicle->km, 0, '', '.'),
            'km_extenso'            => Tools::spellNumber($vehicle->km),
            'combustivel'           => $vehicle->fuel ?? 'Valor não especificado',
            'motor_potencia'        => $vehicle->engine_power ?? 'Valor não especificado',
            'direcao'               => $vehicle->steering ?? 'Valor não especificado',
            'transmissao'           => $vehicle->transmission ?? 'Valor não especificado',
            'portas'                => $vehicle->doors ?? 'Valor não especificado',
            'portas_extenso'        => Tools::spellNumber($vehicle->doors),
            'lugares'               => $vehicle->seats ?? 'Valor não especificado',
            'lugares_extenso'       => Tools::spellNumber($vehicle->seats),
            'tracao'                => $vehicle->traction ?? 'Valor não especificado',
            'cor'                   => $vehicle->color ?? 'Valor não especificado',
            'placa'                 => $vehicle->plate ?? 'Valor não especificado',
            'chassi'                => $vehicle->chassi ?? 'Valor não especificado',
            'renavam'               => $vehicle->renavam ?? 'Valor não especificado',
            'descricao'             => $vehicle->description ?? 'Valor não especificado',
            'anotacao'              => $vehicle->annotation ?? 'Valor não especificado',
        ]);
    }

    public static function setSupplierValues(TemplateProcessor $template, ?int $supplier_id): void
    {
        if ($supplier_id === null) {
            return;
        }

        $supplier = Supplier::with('address', 'address.city')->find($supplier_id);

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
            'fornecedor_data_de_nascimento'         => Tools::dateFormat($supplier->birth_date),
            'fornecedor_data_de_nascimento_extenso' => Tools::spellDate($supplier->birth_date),
            'fornecedor_pai'                        => $supplier->father ?? 'Valor não especificado',
            'fornecedor_telefone_pai'               => $supplier->father_phone ?? 'Valor não especificado',
            'fornecedor_mae'                        => $supplier->mother ?? 'Valor não especificado',
            'fornecedor_telefone_mae'               => $supplier->mother_phone ?? 'Valor não especificado',
            'fornecedor_afiliado_1'                 => $supplier->affiliated_one ?? 'Valor não especificado',
            'fornecedor_telefone_afiliado_1'        => $supplier->affiliated_one_phone ?? 'Valor não especificado',
            'fornecedor_afiliado_2'                 => $supplier->affiliated_two ?? 'Valor não especificado',
            'fornecedor_telefone_afiliado_2'        => $supplier->affiliated_two_phone ?? 'Valor não especificado',
            'fornecedor_descricao'                  => $supplier->description ?? 'Valor não especificado',
        ]);

        //Substitui os placeholders com os dados do endereco do fornecedor
        $template->setValues([
            'fornecedor_endereco_cep'         => $supplier->address->zip_code ?? 'Valor não especificado',
            'fornecedor_endereco_rua'         => $supplier->address->street ?? 'Valor não especificado',
            'fornecedor_endereco_numero'      => $supplier->address->number ?? 'Valor não especificado',
            'fornecedor_endereco_bairro'      => $supplier->address->neighborhood ?? 'Valor não especificado',
            'fornecedor_endereco_cidade'      => $supplier->address->city->name ?? 'Valor não especificado',
            'fornecedor_endereco_estado'      => $supplier->address->state ?? 'Valor não especificado',
            'fornecedor_endereco_complemento' => $supplier->address->complement ?? 'Valor não especificado',
        ]);
    }

    public static function setSaleValues(TemplateProcessor $template, ?int $sale_id): void
    {
        if ($sale_id === null) {
            return;
        }

        $sale = Sale::find($sale_id);//@phpstan-ignore-line

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
    }

    public static function setInstallmentValues(TemplateProcessor $template, ?int $installment_id): void
    {
        if ($installment_id === null) {
            return;
        }

        $installment = PaymentInstallments::find($installment_id); //@phpstan-ignore-line

        $sale = Sale::with('paymentInstallments')->find($installment->sale_id);

        $iteration = 1;

        foreach ($sale->paymentInstallments as $i) { //@phpstan-ignore-line
            if ($i->id === $installment->id) {
                $template->setValues([
                    "parcela_numero"         => $iteration,
                    "parcela_numero_extenso" => Tools::spellNumber($iteration),
                ]);

                break;
            }
            $iteration++;
        }

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
    }

    public static function setInstallmentsValues(TemplateProcessor $template, ?int $sale_id): void
    {
        if ($sale_id === null) {
            return;
        }

        $installments = PaymentInstallments::where('sale_id', $sale_id)->orderBy('due_date')->get(); //@phpstan-ignore-line

        if ($installments->first() === null) {
            return;
        }

        //Substitui os placeholders com os dados das parcelas
        $template->setValues([
            'quantidade_parcelas'         => $installments->count(),
            'quantidade_parcelas_extenso' => Tools::spellNumber($installments->count()),
            'parcelas_restantes'          => $installments->whereNull('payment_date')->count(),
            'parcelas_restantes_extenso'  => Tools::spellNumber($installments->whereNull('payment_date')->count()),
        ]);

        for ($i = 0; $i < $installments->count(); $i++) {
            $template->setValues([
                "parcela_" . ($i + 1) . "_data_vencimento"          => Tools::dateFormat($installments[$i]->due_date),
                "parcela_" . ($i + 1) . "_data_vencimento_extenso"  => Tools::spellDate($installments[$i]->due_date),
                "parcela_" . ($i + 1) . "_valor"                    => number_format($installments[$i]->value, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_valor_extenso"            => Tools::spellNumber($installments[$i]->value),
                "parcela_" . ($i + 1) . "_valor_dinheiro"           => Tools::spellMonetary($installments[$i]->value),
                "parcela_" . ($i + 1) . "_status"                   => $installments[$i]->status ?? 'Valor não especificado',
                "parcela_" . ($i + 1) . "_data_pagamento"           => Tools::dateFormat($installments[$i]->payment_date),
                "parcela_" . ($i + 1) . "_data_pagamento_extenso"   => Tools::spellDate($installments[$i]->payment_date),
                "parcela_" . ($i + 1) . "_valor_pagamento"          => number_format($installments[$i]->payment_value, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_valor_pagamento_extenso"  => Tools::spellNumber($installments[$i]->payment_value),
                "parcela_" . ($i + 1) . "_valor_pagamento_dinheiro" => Tools::spellMonetary($installments[$i]->payment_value),
                "parcela_" . ($i + 1) . "_metodo_pagamento"         => $installments[$i]->payment_method,
                "parcela_" . ($i + 1) . "_desconto"                 => number_format($installments[$i]->discount, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_desconto_extenso"         => Tools::spellNumber($installments[$i]->discount),
                "parcela_" . ($i + 1) . "_desconto_dinheiro"        => Tools::spellMonetary($installments[$i]->discount),
                "parcela_" . ($i + 1) . "_acrescimo"                => number_format($installments[$i]->surcharge, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_acrescimo_extenso"        => Tools::spellNumber($installments[$i]->surcharge),
                "parcela_" . ($i + 1) . "_acrescimo_dinheiro"       => Tools::spellMonetary($installments[$i]->surcharge),
            ]);
        }
    }

    public static function setExpenseValues(TemplateProcessor $template, ?int $expense_id): void
    {
        if ($expense_id === null) {
            return;
        }

        $expense = VehicleExpense::find($expense_id);//@phpstan-ignore-line

        if ($expense === null) {
            return;
        }

        //Substitui os placeholders com os dados da parcela
        $template->setValues([
            "despesa_data"           => Tools::dateFormat($expense->date),
            "despesa_data_extenso"   => Tools::spellDate($expense->date),
            "despesa_descricao"      => $expense->description,
            "despesa_valor"          => number_format($expense->value, 2, ',', '.'),
            "despesa_valor_extenso"  => Tools::spellNumber($expense->value),
            "despesa_valor_dinheiro" => Tools::spellMonetary($expense->value),
        ]);
    }

    public static function setExpensesValues(TemplateProcessor $template, ?int $vehicle_id): void
    {
        if ($vehicle_id === null) {
            return;
        }

        $expenses = VehicleExpense::where('vehicle_id', $vehicle_id)->orderBy('date')->get(); //@phpstan-ignore-line

        if ($expenses->first() === null) {
            return;
        }

        $descriptions = '';

        foreach ($expenses as $expense) {
            $descriptions .= $expense->description . "\n";
        }

        //Substitui os placeholders com os dados das parcelas
        $template->setValues([
            "despesa_descricoes"          => $descriptions,
            'quantidade_despesas'         => $expenses->count(),
            'quantidade_despesas_extenso' => Tools::spellNumber($expenses->count()),
            "despesa_total"               => number_format($expenses->sum('value'), 2, ',', '.'),
            "despesa_total_extenso"       => Tools::spellNumber($expenses->sum('value')),
            "despesa_total_dinheiro"      => Tools::spellMonetary($expenses->sum('value')),
        ]);
    }

    public static function generatePurchaseContract(TemplateProcessor $template, Vehicle $vehicle): string
    {
        // Substitui os placeholders com os dados do usuario
        self::setUserValues($template, $vehicle->user_id ?? null);

        //Substitui os placeholders com os dados do veiculo
        self::setVehicleValues($template, $vehicle->id ?? null);

        //Substitui os placeholders com os dados do fornecedor
        self::setSupplierValues($template, $vehicle->supplier->id ?? null);

        //Substitui os placeholders com os dados das despesas
        self::setExpensesValues($template, $vehicle->id ?? null);

        // Salva o contrato preenchido
        file_exists(public_path('storage\contracts')) ?: Storage::makeDirectory('public\contracts');
        $name                      = $vehicle->supplier->name ?? 'Valor não especificado';
        $caminhoContratoPreenchido = "storage/contracts/Contrato de Compra - {$name}.docx";
        $template->saveAs($caminhoContratoPreenchido);

        return $caminhoContratoPreenchido;
    }

    public static function generateSaleContract(TemplateProcessor $template, Sale $sale): string
    {
        // Substitui os placeholders com os dados do usuario
        self::setUserValues($template, $sale->user_id ?? null);

        // Substitui os placeholders com os dados do cliente
        self::setClientValues($template, $sale->client->id ?? null);

        //Substitui os placeholders com os dados do veiculo
        self::setVehicleValues($template, $sale->vehicle->id ?? null);

        //Substitui os placeholders com os dados do fornecedor
        self::setSupplierValues($template, $sale->vehicle->supplier->id ?? null);

        //Substitui os placeholders com os dados da venda
        self::setSaleValues($template, $sale->id ?? null);

        //Substitui os placeholders com os dados das parcelas
        self::setInstallmentsValues($template, $sale->id ?? null);

        //Substitui os placeholders com os dados das despesas
        self::setExpensesValues($template, $sale->vehicle->id ?? null);

        // Salva o contrato preenchido
        file_exists(public_path('storage\contracts')) ?: Storage::makeDirectory('public\contracts');
        $caminhoContratoPreenchido = "storage/contracts/Contrato - {$sale->client->name}.docx";
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

        //Substitui os placeholders com os dados das parcelas
        self::setInstallmentsValues($template, $installment->sale->id ?? null);

        //Substitui os placeholders com os dados das despesas
        self::setExpensesValues($template, $installment->sale->vehicle->id ?? null);

        // Salva o contrato preenchido
        file_exists(public_path('storage\contracts')) ?: Storage::makeDirectory('public\contracts');
        $caminhoContratoPreenchido = "storage/contracts/Recibo - {$installment->sale->client->name}.docx";
        $template->saveAs($caminhoContratoPreenchido);

        return $caminhoContratoPreenchido;
    }
}
