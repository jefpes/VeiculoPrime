<?php

namespace App\Tools;

use App\Models\Vehicle;
use App\Models\{PaymentInstallment, People, Sale, User, VehicleExpense};
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\{Storage};
use PhpOffice\PhpWord\TemplateProcessor;

class Contracts
{
    public static function setUserValues(TemplateProcessor $template, ?string $user_id = null): void
    {
        $user = User::with('people.employee', 'people.address', 'people.phone');

        // Substitui os placeholders com os dados do usuario
        if ($user_id !== null) {
            $user = $user->find($user_id);
        }

        if ($user_id === null) {
            $user = $user->find(auth_user()->id);
        }

        if ($user === null) {
            return;
        }

        if ($user->people !== null) {
            // Substitui os placeholders com os dados do usuario
            $person = $user->people;

            $template->setValues([
                'usuario_email' => $user->email ?? 'Valor não especificado',
            ]);

            self::setPeopleValues($template, $person->id, 'usuario');
        }
    }

    public static function setPeopleValues(TemplateProcessor $template, ?string $people_id = null, string $parent = null): void
    {
        if ($people_id === null) {
            return;
        }

        $person = People::query()->find($people_id);

        if ($person !== null) {
            $template->setValues([
                $parent === null ? "nome" : "{$parent}_nome"                                       => $person->name ?? 'Valor não especificado',
                $parent === null ? "sexo" : "{$parent}_sexo"                                       => $person->sex->value ?? 'Valor não especificado', //@phpstan-ignore-line
                $parent === null ? "type" : "{$parent}_tipo"                                       => $person->person_type->value ?? 'Valor não especificado', //@phpstan-ignore-line
                $parent === null ? "cpf_cnpj" : "{$parent}_cpf_cnpj"                               => $person->person_id ?? 'Valor não especificado',
                $parent === null ? "rg" : "{$parent}_rg"                                           => $person->rg ?? 'Valor não especificado',
                $parent === null ? "email" : "{$parent}_email"                                     => $person->email ?? 'Valor não especificado',
                $parent === null ? "data_nascimento" : "{$parent}_data_nascimento"                 => date_format_custom($person->birthday),
                $parent === null ? "data_nascimento_extenso" : "{$parent}_data_nascimento_extenso" => spell_date($person->birthday),
                $parent === null ? "pai" : "{$parent}_pai"                                         => $person->father ?? 'Valor não especificado',
                $parent === null ? "mae" : "{$parent}_mae"                                         => $person->mother ?? 'Valor não especificado',
                $parent === null ? "estado_civil" : "{$parent}_estado_civil"                       => $person->marital_status ?? 'Valor não especificado',
                $parent === null ? "conjuje" : "{$parent}_conjuje"                                 => $person->spouse ?? 'Valor não especificado',
            ]);
        }
    }

    public static function setEmployeeValues(TemplateProcessor $template, ?string $people_id = null, string $parent = null): void
    {
        if ($people_id === null) {
            return;
        }

        $employee = People::query()->find($people_id)->employee()->last(); //@phpstan-ignore-line

        if ($employee !== null) {
            $template->setValues([
                $parent === null ? 'funcionario_salario' : "{$parent}_funcionario_salario"                                                 => number_format($employee->salary, 2, ',', '.'),
                $parent === null ? 'funcionario_salario_extenso' : "{$parent}_funcionario_salario_extenso"                                 => spell_number($employee->salary),
                $parent === null ? 'funcionario_salario_dinheiro' : "{$parent}_funcionario_salario_dinheiro"                               => spell_monetary($employee->salary),
                $parent === null ? 'funcionario_pessoa_data_contratacao' : "{$parent}_funcionario_pessoa_data_contratacao"                 => date_format_custom($employee->admission_date),
                $parent === null ? 'funcionario_pessoa_data_contratacao_extenso' : "{$parent}_funcionario_pessoa_data_contratacao_extenso" => spell_date($employee->admission_date),
                $parent === null ? 'funcionario_pessoa_data_demissao' : "{$parent}_funcionario_pessoa_data_demissao"                       => date_format_custom($employee->resignation_date),
                $parent === null ? 'funcionario_pessoa_data_demissao_extenso' : "{$parent}_funcionario_pessoa_data_demissao_extenso"       => spell_date($employee->resignation_date),
            ]);
        }
    }

    public static function setAddressesValues(TemplateProcessor $template, ?MorphMany $addressable = null, string $parent = null): void
    {
        if ($addressable === null || $parent === null) {
            return;
        }

        $iteration = 0;

        foreach ($addressable->get() as $address) {
            $iteration++;
            $template->setValues([
                "{$parent}_endereco_cep_{$iteration}"         => $address->zip_code ?? 'Valor não especificado',
                "{$parent}_endereco_rua_{$iteration}"         => $address->street ?? 'Valor não especificado',
                "{$parent}_endereco_numero_{$iteration}"      => $address->number ?? 'Valor não especificado',
                "{$parent}_endereco_bairro_{$iteration}"      => $address->neighborhood ?? 'Valor não especificado',
                "{$parent}_endereco_cidade_{$iteration}"      => $address->city ?? 'Valor não especificado',
                "{$parent}_endereco_estado_{$iteration}"      => $address->state ?? 'Valor não especificado',
                "{$parent}_endereco_complemento_{$iteration}" => $address->complement ?? 'Valor não especificado',
            ]);
        }
    }

    public static function setAffiliatesValues(TemplateProcessor $template, ?MorphMany $affiliatable = null, ?string $parent = null): void
    {
        if ($affiliatable === null) {
            return;
        }

        $iteration = 0;

        foreach ($affiliatable->get() as $affiliate) {
            $iteration++;
            $template->setValues([
                "{$parent}_afiliado_tipo_{$iteration}"     => $affiliate->type ?? 'Valor não especificado',
                "{$parent}_afiliado_nome_{$iteration}"     => $affiliate->name ?? 'Valor não especificado',
                "{$parent}_afiliado_telefone_{$iteration}" => $affiliate->phone ?? 'Valor não especificado',
            ]);
        }
    }

    public static function setVehicleValues(TemplateProcessor $template, ?string $vehicle_id): void
    {
        if ($vehicle_id === null) {
            return;
        }

        $vehicle = Vehicle::with('model', 'model.type', 'model.brand')->find($vehicle_id);

        //Substitui os placeholders com os dados do veiculo
        $template->setValues([
            'data_compra'           => date_format_custom($vehicle->purchase_date),
            'data_compra_extenso'   => spell_date($vehicle->purchase_date),
            'preco_fipe'            => number_format($vehicle->fipe_price, 2, ',', '.'),
            'preco_fipe_extenso'    => spell_number($vehicle->fipe_price),
            'preco_fipe_dinheiro'   => spell_monetary($vehicle->fipe_price),
            'preco_compra'          => number_format($vehicle->purchase_price, 2, ',', '.'),
            'preco_compra_extenso'  => spell_number($vehicle->purchase_price),
            'preco_compra_dinheiro' => spell_monetary($vehicle->purchase_price),
            'preco_venda'           => number_format($vehicle->promotional_price ?? $vehicle->sale_price, 2, ',', '.'),
            'preco_venda_extenso'   => spell_number($vehicle->promotional_price ?? $vehicle->sale_price),
            'preco_venda_dinheiro'  => spell_monetary($vehicle->promotional_price ?? $vehicle->sale_price),
            'modelo'                => $vehicle->model->name ?? 'Valor não especificado',
            'tipo'                  => $vehicle->model->type->name ?? 'Valor não especificado',
            'marca'                 => $vehicle->model->brand->name ?? 'Valor não especificado',
            'ano_um'                => $vehicle->year_one ?? 'Valor não especificado',
            'ano_dois'              => $vehicle->year_two ?? 'Valor não especificado',
            'km'                    => number_format($vehicle->km, 0, '', '.'),
            'km_extenso'            => spell_number($vehicle->km),
            'combustivel'           => $vehicle->fuel ?? 'Valor não especificado',
            'motor_potencia'        => $vehicle->engine_power ?? 'Valor não especificado',
            'direcao'               => $vehicle->steering ?? 'Valor não especificado',
            'transmissao'           => $vehicle->transmission ?? 'Valor não especificado',
            'portas'                => $vehicle->doors ?? 'Valor não especificado',
            'portas_extenso'        => spell_number($vehicle->doors),
            'lugares'               => $vehicle->seats ?? 'Valor não especificado',
            'lugares_extenso'       => spell_number($vehicle->seats),
            'tracao'                => $vehicle->traction ?? 'Valor não especificado',
            'cor'                   => $vehicle->color ?? 'Valor não especificado',
            'placa'                 => $vehicle->plate ?? 'Valor não especificado',
            'chassi'                => $vehicle->chassi ?? 'Valor não especificado',
            'renavam'               => $vehicle->renavam ?? 'Valor não especificado',
            'numero_crv'            => $vehicle->crv_number ?? 'Valor não especificado',
            'codigo_crv'            => $vehicle->crv_code ?? 'Valor não especificado',
            'descricao'             => $vehicle->description ?? 'Valor não especificado',
            'anotacao'              => $vehicle->annotation ?? 'Valor não especificado',
        ]);
    }

    public static function setSaleValues(TemplateProcessor $template, ?string $sale_id): void
    {
        if ($sale_id === null) {
            return;
        }

        $sale = Sale::find($sale_id);//@phpstan-ignore-line

        //Substitui os placeholders com os dados da venda
        $template->setValues([
            'metodo_de_pagamento'       => $sale->payment_method ?? 'Valor não especificado',
            'status'                    => $sale->status ?? 'Valor não especificado',
            'data_venda'                => date_format_custom($sale->date_sale),
            'data_venda_extenso'        => spell_date($sale->date_sale),
            'data_pagamento'            => date_format_custom($sale->date_payment),
            'data_pagamento_extenso'    => spell_date($sale->date_payment),
            'taxa_juros'                => number_format($sale->interest_rate, 2, ',', '.') . '%',
            'taxa_juros_extenso'        => spell_percentage($sale->interest_rate),
            'desconto'                  => number_format($sale->discount, 2, ',', '.'),
            'desconto_extenso'          => spell_number($sale->discount),
            'desconto_dinheiro'         => spell_monetary($sale->discount),
            'juros'                     => number_format($sale->interest, 2, ',', '.'),
            'juros_extenso'             => spell_number($sale->interest),
            'juros_dinheiro'            => spell_monetary($sale->interest),
            'entrada'                   => number_format($sale->down_payment, 2, ',', '.'),
            'entrada_extenso'           => spell_number($sale->down_payment),
            'entrada_dinheiro'          => spell_monetary($sale->down_payment),
            'numero_parcelas'           => $sale->number_installments,
            'numero_parcelas_extenso'   => spell_number($sale->number_installments),
            'reembolso'                 => number_format($sale->reimbursement, 2, ',', '.'),
            'reembolso_extenso'         => spell_number($sale->reimbursement),
            'reembolso_dinheiro'        => spell_monetary($sale->reimbursement),
            'data_cancelamento'         => date_format_custom($sale->date_cancel),
            'data_cancelamento_extenso' => spell_date($sale->date_cancel),
            'total'                     => number_format($sale->total, 2, ',', '.'),
            'total_extenso'             => spell_number($sale->total),
            'total_dinheiro'            => spell_monetary($sale->total),
            'total_com_juros'           => number_format($sale->total_with_interest, 2, ',', '.'),
            'total_com_juros_extenso'   => spell_number($sale->total_with_interest),
            'total_com_juros_dinheiro'  => spell_monetary($sale->total_with_interest),
        ]);
    }

    public static function setInstallmentValues(TemplateProcessor $template, ?string $installment_id): void
    {
        if ($installment_id === null) {
            return;
        }

        $installment = PaymentInstallment::find($installment_id); //@phpstan-ignore-line

        $sale = Sale::with('paymentInstallments')->find($installment->sale_id);

        $iteration = 1;

        foreach ($sale->paymentInstallments as $i) { //@phpstan-ignore-line
            if ($i->id === $installment->id) {
                $template->setValues([
                    "parcela_numero"         => $iteration,
                    "parcela_numero_extenso" => spell_number($iteration),
                ]);

                break;
            }
            $iteration++;
        }

        //Substitui os placeholders com os dados da parcela
        $template->setValues([
            "parcela_data_vencimento"          => date_format_custom($installment->due_date),
            "parcela_data_vencimento_extenso"  => spell_date($installment->due_date),
            "parcela_valor"                    => number_format($installment->value, 2, ',', '.'),
            "parcela_valor_extenso"            => spell_number($installment->value),
            "parcela_valor_dinheiro"           => spell_monetary($installment->value),
            "parcela_status"                   => $installment->status ?? 'Valor não especificado',
            "parcela_data_pagamento"           => date_format_custom($installment->payment_date),
            "parcela_data_pagamento_extenso"   => spell_date($installment->payment_date),
            "parcela_multa"                    => number_format($installment->late_fee, 2, ',', '.'),
            "parcela_multa_extenso"            => spell_number($installment->late_fee),
            "parcela_multa_dinheiro"           => spell_monetary($installment->late_fee),
            "parcela_taxa_juros"               => number_format($installment->interest_rate, 2, ',', '.') . '%',
            "parcela_taxa_juros_extenso"       => spell_percentage($installment->interest_rate),
            "parcela_juros"                    => number_format($installment->interest, 2, ',', '.'),
            "parcela_juros_extenso"            => spell_number($installment->interest),
            "parcela_juros_dinheiro"           => spell_monetary($installment->interest),
            "parcela_valor_pagamento"          => number_format($installment->payment_value, 2, ',', '.'),
            "parcela_valor_pagamento_extenso"  => spell_number($installment->payment_value),
            "parcela_valor_pagamento_dinheiro" => spell_monetary($installment->payment_value),
            "parcela_metodo_pagamento"         => $installment->payment_method ?? 'Valor não especificado',
            "parcela_desconto"                 => number_format($installment->discount, 2, ',', '.'),
            "parcela_desconto_extenso"         => spell_number($installment->discount),
            "parcela_desconto_dinheiro"        => spell_monetary($installment->discount),
        ]);
    }

    public static function setInstallmentsValues(TemplateProcessor $template, ?string $sale_id): void
    {
        if ($sale_id === null) {
            return;
        }

        $installments = PaymentInstallment::where('sale_id', $sale_id)->orderBy('due_date')->get(); //@phpstan-ignore-line

        if ($installments->first() === null) {
            return;
        }

        //Substitui os placeholders com os dados das parcelas
        $template->setValues([
            'quantidade_parcelas'         => $installments->count(),
            'quantidade_parcelas_extenso' => spell_number($installments->count()),
            'parcelas_restantes'          => $installments->whereNull('payment_date')->count(),
            'parcelas_restantes_extenso'  => spell_number($installments->whereNull('payment_date')->count()),
        ]);

        for ($i = 0; $i < $installments->count(); $i++) {
            $template->setValues([
                "parcela_" . ($i + 1) . "_data_vencimento"          => date_format_custom($installments[$i]->due_date),
                "parcela_" . ($i + 1) . "_data_vencimento_extenso"  => spell_date($installments[$i]->due_date),
                "parcela_" . ($i + 1) . "_valor"                    => number_format($installments[$i]->value, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_valor_extenso"            => spell_number($installments[$i]->value),
                "parcela_" . ($i + 1) . "_valor_dinheiro"           => spell_monetary($installments[$i]->value),
                "parcela_" . ($i + 1) . "_status"                   => $installments[$i]->status ?? 'Valor não especificado',
                "parcela_" . ($i + 1) . "_data_pagamento"           => date_format_custom($installments[$i]->payment_date),
                "parcela_" . ($i + 1) . "_data_pagamento_extenso"   => spell_date($installments[$i]->payment_date),
                "parcela_" . ($i + 1) . "_multa"                    => number_format($installments[$i]->late_fee, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_multa_extenso"            => spell_number($installments[$i]->late_fee),
                "parcela_" . ($i + 1) . "_multa_dinheiro"           => spell_monetary($installments[$i]->late_fee),
                "parcela_" . ($i + 1) . "_taxa_juros"               => number_format($installments[$i]->interest_rate, 2, ',', '.') . '%',
                "parcela_" . ($i + 1) . "_taxa_juros_extenso"       => spell_percentage($installments[$i]->interest_rate),
                "parcela_" . ($i + 1) . "_juros"                    => number_format($installments[$i]->interest, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_juros_extenso"            => spell_number($installments[$i]->interest),
                "parcela_" . ($i + 1) . "_juros_dinheiro"           => spell_monetary($installments[$i]->interest),
                "parcela_" . ($i + 1) . "_valor_pagamento"          => number_format($installments[$i]->payment_value, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_valor_pagamento_extenso"  => spell_number($installments[$i]->payment_value),
                "parcela_" . ($i + 1) . "_valor_pagamento_dinheiro" => spell_monetary($installments[$i]->payment_value),
                "parcela_" . ($i + 1) . "_metodo_pagamento"         => $installments[$i]->payment_method,
                "parcela_" . ($i + 1) . "_desconto"                 => number_format($installments[$i]->discount, 2, ',', '.'),
                "parcela_" . ($i + 1) . "_desconto_extenso"         => spell_number($installments[$i]->discount),
                "parcela_" . ($i + 1) . "_desconto_dinheiro"        => spell_monetary($installments[$i]->discount),
            ]);
        }
    }

    public static function setExpenseValues(TemplateProcessor $template, ?string $expense_id): void
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
            "despesa_data"           => date_format_custom($expense->date),
            "despesa_data_extenso"   => spell_date($expense->date),
            "despesa_descricao"      => $expense->description,
            "despesa_valor"          => number_format($expense->value, 2, ',', '.'),
            "despesa_valor_extenso"  => spell_number($expense->value),
            "despesa_valor_dinheiro" => spell_monetary($expense->value),
        ]);
    }

    public static function setExpensesValues(TemplateProcessor $template, ?string $vehicle_id): void
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
            'quantidade_despesas_extenso' => spell_number($expenses->count()),
            "despesa_total"               => number_format($expenses->sum('value'), 2, ',', '.'),
            "despesa_total_extenso"       => spell_number($expenses->sum('value')),
            "despesa_total_dinheiro"      => spell_monetary($expenses->sum('value')),
        ]);
    }

    public static function generatePurchaseContract(TemplateProcessor $template, Vehicle $vehicle): string
    {
        // Substitui os placeholders com os dados do usuario
        self::setUserValues($template, $vehicle->user_id ?? null);

        //Substitui os placeholders com os dados do veiculo
        self::setVehicleValues($template, $vehicle->id ?? null);

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
        self::setUserValues($template, $sale->seller_id);
        // dump('user foi chamado');

        self::setPeopleValues($template, $sale->client_id, 'cliente');
        // dump('people-client foi chamado');

        self::setAddressesValues($template, $sale->client->addresses(), 'cliente');
        // dump('addresses-client foi chamado');

        self::setAffiliatesValues($template, $sale->client->affiliates(), 'cliente');
        // dump('affiliates foi chamado');

        self::setVehicleValues($template, $sale->vehicle->id ?? null);
        // dump('vehicle foi chamado');

        self::setPeopleValues($template, $sale->vehicle->supplier->id ?? null, 'fornecedor');
        // dump('people-suplier foi chamado');

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

    public static function generateReceiptContract(TemplateProcessor $template, PaymentInstallment $installment): string
    {
        // Substitui os placeholders com os dados do usuario
        self::setUserValues($template);

        //TODO: Fix this
        //self::setClientValues($template, $installment->sale->people->id ?? null);

        //Substitui os placeholders com os dados do veiculo
        self::setVehicleValues($template, $installment->sale->vehicle->id ?? null);

        //TODO: Fix this
        //self::setSupplierValues($template, $installment->sale->vehicle->supplier->id ?? null);

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
