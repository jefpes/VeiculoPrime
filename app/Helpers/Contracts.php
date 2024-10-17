<?php

namespace App\Helpers;

use App\Models\{PaymentInstallments, Sale, User};
use Illuminate\Support\Facades\{Auth, Storage};
use PhpOffice\PhpWord\TemplateProcessor;

class Contracts
{
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
        $template->setValue('cliente_nome', $installment->sale->client->name); //@phpstan-ignore-line
        $template->setValue('cliente_genero', $installment->sale->client->gender); //@phpstan-ignore-line
        $template->setValue('cliente_tipo', $installment->sale->client->client_type); //@phpstan-ignore-line
        $template->setValue('cliente_rg', $installment->sale->client->rg); //@phpstan-ignore-line
        $template->setValue('cliente_cpf/cnpj', $installment->sale->client->client_id); //@phpstan-ignore-line
        $template->setValue('cliente_estado_civil', $installment->sale->client->marital_status); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_1', $installment->sale->client->phone_one); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_2', $installment->sale->client->phone_two); //@phpstan-ignore-line
        $template->setValue('cliente_data_de_nascimento', Tools::dateFormat($installment->sale->client->birth_date)); //@phpstan-ignore-line
        $template->setValue('cliente_data_de_nascimento_extenso', Tools::spellDate($installment->sale->client->birth_date)); //@phpstan-ignore-line
        $template->setValue('cliente_pai', $installment->sale->client->father); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_pai', $installment->sale->client->father_phone); //@phpstan-ignore-line
        $template->setValue('cliente_mae', $installment->sale->client->mother); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_mae', $installment->sale->client->mother_phone); //@phpstan-ignore-line
        $template->setValue('cliente_afiliado_1', $installment->sale->client->affiliated_one); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_afiliado_1', $installment->sale->client->affiliated_one_phone); //@phpstan-ignore-line
        $template->setValue('cliente_afiliado_2', $installment->sale->client->affiliated_two); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_afiliado_2', $installment->sale->client->affiliated_two_phone); //@phpstan-ignore-line
        $template->setValue('cliente_descricao', $installment->sale->client->description); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do endereco do cliente
        $template->setValue('cliente_endereco_cep', $installment->sale->client->address->zip_code); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_rua', $installment->sale->client->address->street); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_numero', $installment->sale->client->address->number); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_bairro', $installment->sale->client->address->neighborhood); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_cidade', $installment->sale->client->address->city->name); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_estado', $installment->sale->client->address->state); //@phpstan-ignore-line
        $template->setValue('cliente_endereco_complemento', $installment->sale->client->address->complement); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do veiculo
        $template->setValue('data_compra', Tools::dateFormat($installment->sale->vehicle->purchase_date)); //@phpstan-ignore-line
        $template->setValue('data_compra_extenso', Tools::spellDate($installment->sale->vehicle->purchase_date)); //@phpstan-ignore-line
        $template->setValue('preco_fipe', number_format($installment->sale->vehicle->fipe_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('preco_fipe_extenso', Tools::spellNumber($installment->sale->vehicle->fipe_price)); //@phpstan-ignore-line
        $template->setValue('preco_fipe_dinheiro', Tools::spellMonetary($installment->sale->vehicle->fipe_price)); //@phpstan-ignore-line
        $template->setValue('preco_compra', number_format($installment->sale->vehicle->purchase_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('preco_compra_extenso', Tools::spellNumber($installment->sale->vehicle->purchase_price)); //@phpstan-ignore-line
        $template->setValue('preco_compra_dinheiro', Tools::spellMonetary($installment->sale->vehicle->purchase_price)); //@phpstan-ignore-line
        $template->setValue('preco_venda', number_format($installment->sale->vehicle->promotional_price ?? $installment->sale->vehicle->sale_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('preco_venda_extenso', Tools::spellNumber($installment->sale->vehicle->promotional_price ?? $installment->sale->vehicle->sale_price)); //@phpstan-ignore-line
        $template->setValue('preco_venda_dinheiro', Tools::spellMonetary($installment->sale->vehicle->promotional_price ?? $installment->sale->vehicle->sale_price)); //@phpstan-ignore-line
        $template->setValue('modelo', $installment->sale->vehicle->model->name); //@phpstan-ignore-line
        $template->setValue('tipo', $installment->sale->vehicle->model->type->name); //@phpstan-ignore-line
        $template->setValue('marca', $installment->sale->vehicle->model->brand->name); //@phpstan-ignore-line
        $template->setValue('ano_um', $installment->sale->vehicle->year_one); //@phpstan-ignore-line
        $template->setValue('ano_dois', $installment->sale->vehicle->year_two); //@phpstan-ignore-line
        $template->setValue('km', number_format($installment->sale->vehicle->km, 0, '', '.')); //@phpstan-ignore-line
        $template->setValue('km_extenso', Tools::spellNumber($installment->sale->vehicle->km)); //@phpstan-ignore-line
        $template->setValue('combustivel', $installment->sale->vehicle->fuel); //@phpstan-ignore-line
        $template->setValue('motor_potencia', $installment->sale->vehicle->engine_power); //@phpstan-ignore-line
        $template->setValue('direcao', $installment->sale->vehicle->steering); //@phpstan-ignore-line
        $template->setValue('transmissao', $installment->sale->vehicle->transmission); //@phpstan-ignore-line
        $template->setValue('portas', $installment->sale->vehicle->doors); //@phpstan-ignore-line
        $template->setValue('portas_extenso', Tools::spellNumber($installment->sale->vehicle->doors)); //@phpstan-ignore-line
        $template->setValue('lugares', $installment->sale->vehicle->seats); //@phpstan-ignore-line
        $template->setValue('lugares_extenso', Tools::spellNumber($installment->sale->vehicle->seats)); //@phpstan-ignore-line
        $template->setValue('tracao', $installment->sale->vehicle->traction); //@phpstan-ignore-line
        $template->setValue('cor', $installment->sale->vehicle->color); //@phpstan-ignore-line
        $template->setValue('placa', $installment->sale->vehicle->plate); //@phpstan-ignore-line
        $template->setValue('chassi', $installment->sale->vehicle->chassi); //@phpstan-ignore-line
        $template->setValue('renavam', $installment->sale->vehicle->renavam); //@phpstan-ignore-line
        $template->setValue('descricao', $installment->sale->vehicle->description); //@phpstan-ignore-line
        $template->setValue('anotacao', $installment->sale->vehicle->annotation); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do fornecedor
        if ($installment->sale->supplier !== null) { //@phpstan-ignore-line
            $template->setValue('fornecedor_nome', $installment->sale->supplier->name); //@phpstan-ignore-line
            $template->setValue('fornecedor_genero', $installment->sale->supplier->gender); //@phpstan-ignore-line
            $template->setValue('fornecedor_tipo', $installment->sale->supplier->client_type); //@phpstan-ignore-line
            $template->setValue('fornecedor_rg', $installment->sale->supplier->rg); //@phpstan-ignore-line
            $template->setValue('fornecedor_cpf/cnpj', $installment->sale->supplier->client_id); //@phpstan-ignore-line
            $template->setValue('fornecedor_estado_civil', $installment->sale->supplier->marital_status); //@phpstan-ignore-line
            $template->setValue('fornecedor_telefone_1', $installment->sale->supplier->phone_one); //@phpstan-ignore-line
            $template->setValue('fornecedor_telefone_2', $installment->sale->supplier->phone_two); //@phpstan-ignore-line
            $template->setValue('fornecedor_data_de_nascimento', Tools::dateFormat($installment->sale->supplier->birth_date)); //@phpstan-ignore-line
            $template->setValue('fornecedor_data_de_nascimento_extenso', Tools::spellDate($installment->sale->supplier->birth_date)); //@phpstan-ignore-line
            $template->setValue('fornecedor_pai', $installment->sale->supplier->father); //@phpstan-ignore-line
            $template->setValue('fornecedor_telefone_pai', $installment->sale->supplier->father_phone); //@phpstan-ignore-line
            $template->setValue('fornecedor_mae', $installment->sale->supplier->mother); //@phpstan-ignore-line
            $template->setValue('fornecedor_telefone_mae', $installment->sale->supplier->mother_phone); //@phpstan-ignore-line
            $template->setValue('fornecedor_afiliado_1', $installment->sale->supplier->affiliated_one); //@phpstan-ignore-line
            $template->setValue('fornecedor_telefone_afiliado_1', $installment->sale->supplier->affiliated_one_phone); //@phpstan-ignore-line
            $template->setValue('fornecedor_afiliado_2', $installment->sale->supplier->affiliated_two); //@phpstan-ignore-line
            $template->setValue('fornecedor_telefone_afiliado_2', $installment->sale->supplier->affiliated_two_phone); //@phpstan-ignore-line
            $template->setValue('fornecedor_descricao', $installment->sale->supplier->description); //@phpstan-ignore-line

            //Substitui os placeholders com os dados do endereco do fornecedor
            $template->setValue('fornecedor_endereco_cep', $installment->sale->supplier->address->zip_code); //@phpstan-ignore-line
            $template->setValue('fornecedor_endereco_rua', $installment->sale->supplier->address->street); //@phpstan-ignore-line
            $template->setValue('fornecedor_endereco_numero', $installment->sale->supplier->address->number); //@phpstan-ignore-line
            $template->setValue('fornecedor_endereco_bairro', $installment->sale->supplier->address->neighborhood); //@phpstan-ignore-line
            $template->setValue('fornecedor_endereco_cidade', $installment->sale->supplier->address->city->name); //@phpstan-ignore-line
            $template->setValue('fornecedor_endereco_estado', $installment->sale->supplier->address->state); //@phpstan-ignore-line
            $template->setValue('fornecedor_endereco_complemento', $installment->sale->supplier->address->complement); //@phpstan-ignore-line
        }

        //Substitui os placeholders com os dados da venda
        $template->setValue('metodo_de_pagamento', $installment->sale->payment_method); //@phpstan-ignore-line
        $template->setValue('status', $installment->sale->status); //@phpstan-ignore-line
        $template->setValue('data_venda', Tools::dateFormat($installment->sale->date_sale)); //@phpstan-ignore-line
        $template->setValue('data_venda_extenso', Tools::spellDate($installment->sale->date_sale)); //@phpstan-ignore-line
        $template->setValue('data_pagamento', Tools::dateFormat($installment->sale->date_payment)); //@phpstan-ignore-line
        $template->setValue('data_pagamento_extenso', Tools::spellDate($installment->sale->date_payment)); //@phpstan-ignore-line
        $template->setValue('desconto', $installment->sale->discount); //@phpstan-ignore-line
        $template->setValue('desconto_extenso', Tools::spellNumber($installment->sale->discount)); //@phpstan-ignore-line
        $template->setValue('desconto_dinheiro', Tools::spellMonetary($installment->sale->discount)); //@phpstan-ignore-line
        $template->setValue('acrescimo', $installment->sale->surcharge); //@phpstan-ignore-line
        $template->setValue('acrescimo_extenso', Tools::spellNumber($installment->sale->surcharge)); //@phpstan-ignore-line
        $template->setValue('acrescimo_dinheiro', Tools::spellMonetary($installment->sale->surcharge)); //@phpstan-ignore-line
        $template->setValue('entrada', number_format($installment->sale->down_payment, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('entrada_extenso', Tools::spellNumber($installment->sale->down_payment)); //@phpstan-ignore-line
        $template->setValue('entrada_dinheiro', Tools::spellMonetary($installment->sale->down_payment)); //@phpstan-ignore-line
        $template->setValue('numero_parcelas', $installment->sale->number_installments); //@phpstan-ignore-line
        $template->setValue('numero_parcelas_extenso', Tools::spellNumber($installment->sale->number_installments)); //@phpstan-ignore-line
        $template->setValue('reembolso', number_format($installment->sale->reimbursement, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('reembolso_extenso', Tools::spellNumber($installment->sale->reimbursement)); //@phpstan-ignore-line
        $template->setValue('reembolso_dinheiro', Tools::spellMonetary($installment->sale->reimbursement)); //@phpstan-ignore-line
        $template->setValue('data_cancelamento', Tools::dateFormat($installment->sale->date_cancel)); //@phpstan-ignore-line
        $template->setValue('data_cancelamento_extenso', Tools::spellDate($installment->sale->date_cancel)); //@phpstan-ignore-line
        $template->setValue('total', number_format($installment->sale->total, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('total_extenso', Tools::spellNumber($installment->sale->total)); //@phpstan-ignore-line
        $template->setValue('total_dinheiro', Tools::spellMonetary($installment->sale->total)); //@phpstan-ignore-line

        $template->setValues([
            "parcela_data_vencimento"          => Tools::dateFormat($installment->due_date), //@phpstan-ignore-line
            "parcela_data_vencimento_extenso"  => Tools::spellDate($installment->due_date), //@phpstan-ignore-line
            "parcela_valor"                    => number_format($installment->value, 2, ',', '.'), //@phpstan-ignore-line
            "parcela_valor_extenso"            => Tools::spellNumber($installment->value), //@phpstan-ignore-line
            "parcela_valor_dinheiro"           => Tools::spellMonetary($installment->value), //@phpstan-ignore-line
            "parcela_status"                   => $installment->status, //@phpstan-ignore-line
            "parcela_data_pagamento"           => Tools::dateFormat($installment->payment_date), //@phpstan-ignore-line
            "parcela_data_pagamento_extenso"   => Tools::spellDate($installment->payment_date), //@phpstan-ignore-line
            "parcela_valor_pagamento"          => number_format($installment->payment_value, 2, ',', '.'), //@phpstan-ignore-line
            "parcela_valor_pagamento_extenso"  => Tools::spellNumber($installment->payment_value), //@phpstan-ignore-line
            "parcela_valor_pagamento_dinheiro" => Tools::spellMonetary($installment->payment_value), //@phpstan-ignore-line
            "parcela_metodo_pagamento"         => $installment->payment_method, //@phpstan-ignore-line
            "parcela_desconto"                 => number_format($installment->discount, 2, ',', '.'), //@phpstan-ignore-line
            "parcela_desconto_extenso"         => Tools::spellNumber($installment->discount), //@phpstan-ignore-line
            "parcela_desconto_dinheiro"        => Tools::spellMonetary($installment->discount), //@phpstan-ignore-line
            "parcela_acrescimo"                => number_format($installment->surcharge, 2, ',', '.'), //@phpstan-ignore-line
            "parcela_acrescimo_extenso"        => Tools::spellNumber($installment->surcharge), //@phpstan-ignore-line
            "parcela_acrescimo_dinheiro"       => Tools::spellMonetary($installment->surcharge), //@phpstan-ignore-line
        ]);

        // Salva o contrato preenchido
        file_exists(public_path('storage\contracts')) ?: Storage::makeDirectory('public\contracts');
        $caminhoContratoPreenchido = "storage/contracts/Recibo - {$installment->sale->client->name}.docx"; //@phpstan-ignore-line
        $template->saveAs($caminhoContratoPreenchido);

        return $caminhoContratoPreenchido;
    }
}
