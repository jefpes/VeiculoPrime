<?php

namespace App\Helpers;

use App\Models\Sale;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SaleContract
{
    public static function generateContract(TemplateProcessor $template, Sale $sale): BinaryFileResponse
    {
        $template->setValue('cliente_nome', $sale->client->name); //@phpstan-ignore-line
        $template->setValue('cliente_genero', $sale->client->gender); //@phpstan-ignore-line
        $template->setValue('cliente_tipo', $sale->client->client_type); //@phpstan-ignore-line
        $template->setValue('cliente_rg', $sale->client->rg); //@phpstan-ignore-line
        $template->setValue('cliente_cpf/cnpj', $sale->client->client_id); //@phpstan-ignore-line
        $template->setValue('cliente_estdo_civil', $sale->client->marital_status); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_1', $sale->client->phone_one); //@phpstan-ignore-line
        $template->setValue('cliente_telefone_2', $sale->client->phone_two); //@phpstan-ignore-line
        $template->setValue('cliente_data_de_nascimento', Carbon::parse($sale->client->birth_date)->format('d/m/Y')); //@phpstan-ignore-line
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
        $template->setValue('cliente_cep', $sale->client->address->cep); //@phpstan-ignore-line
        $template->setValue('cliente_rua', $sale->client->address->street); //@phpstan-ignore-line
        $template->setValue('cliente_numero', $sale->client->address->number); //@phpstan-ignore-line
        $template->setValue('cliente_bairro', $sale->client->address->neighborhood); //@phpstan-ignore-line
        $template->setValue('cliente_cidade', $sale->client->address->city->name); //@phpstan-ignore-line
        $template->setValue('cliente_estado', $sale->client->address->state); //@phpstan-ignore-line
        $template->setValue('cliente_complemento', $sale->client->address->complement); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do veiculo
        $template->setValue('data_compra', Carbon::parse($sale->vehicle->purchase_date)->format('d/m/Y')); //@phpstan-ignore-line
        $template->setValue('preco_fipe', number_format($sale->vehicle->fipe_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('preco_compra', number_format($sale->vehicle->purchase_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('preco_venda', number_format($sale->vehicle->promotional_price ?? $sale->vehicle->sale_price, 2, ',', '.')); //@phpstan-ignore-line
        $template->setValue('modelo', $sale->vehicle->model->name); //@phpstan-ignore-line
        $template->setValue('tipo', $sale->vehicle->model->type->name); //@phpstan-ignore-line
        $template->setValue('marca', $sale->vehicle->model->brand->name); //@phpstan-ignore-line
        $template->setValue('ano_um', $sale->vehicle->year_one); //@phpstan-ignore-line
        $template->setValue('ano_dois', $sale->vehicle->year_two); //@phpstan-ignore-line
        $template->setValue('km', $sale->vehicle->km); //@phpstan-ignore-line
        $template->setValue('combustivel', $sale->vehicle->fuel); //@phpstan-ignore-line
        $template->setValue('motor_potencia', $sale->vehicle->engine_power); //@phpstan-ignore-line
        $template->setValue('direcao', $sale->vehicle->steering); //@phpstan-ignore-line
        $template->setValue('transmissao', $sale->vehicle->transmission); //@phpstan-ignore-line
        $template->setValue('portas', $sale->vehicle->doors); //@phpstan-ignore-line
        $template->setValue('seats', $sale->vehicle->seats); //@phpstan-ignore-line
        $template->setValue('tracao', $sale->vehicle->traction); //@phpstan-ignore-line
        $template->setValue('cor', $sale->vehicle->color); //@phpstan-ignore-line
        $template->setValue('placa', $sale->vehicle->plate); //@phpstan-ignore-line
        $template->setValue('chassi', $sale->vehicle->chassi); //@phpstan-ignore-line
        $template->setValue('renavam', $sale->vehicle->renavam); //@phpstan-ignore-line
        $template->setValue('descricao', $sale->vehicle->description); //@phpstan-ignore-line
        $template->setValue('anotacao', $sale->vehicle->annotation); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do fornecedor
        $template->setValue('fornecedor_nome', $sale->supplier->name); //@phpstan-ignore-line
        $template->setValue('fornecedor_genero', $sale->supplier->gender); //@phpstan-ignore-line
        $template->setValue('fornecedor_tipo', $sale->supplier->client_type); //@phpstan-ignore-line
        $template->setValue('fornecedor_rg', $sale->supplier->rg); //@phpstan-ignore-line
        $template->setValue('fornecedor_cpf/cnpj', $sale->supplier->client_id); //@phpstan-ignore-line
        $template->setValue('fornecedor_estdo_civil', $sale->supplier->marital_status); //@phpstan-ignore-line
        $template->setValue('fornecedor_telefone_1', $sale->supplier->phone_one); //@phpstan-ignore-line
        $template->setValue('fornecedor_telefone_2', $sale->supplier->phone_two); //@phpstan-ignore-line
        $template->setValue('fornecedor_data_de_nascimento', Carbon::parse($sale->supplier->birth_date)->format('d/m/Y')); //@phpstan-ignore-line
        $template->setValue('fornecedor_pai', $sale->supplier->father); //@phpstan-ignore-line
        $template->setValue('fornecedor_telefone_pai', $sale->supplier->father_phone); //@phpstan-ignore-line
        $template->setValue('fornecedor_mae', $sale->supplier->mother); //@phpstan-ignore-line
        $template->setValue('fornecedor_telefone_mae', $sale->supplier->mother_phone); //@phpstan-ignore-line
        $template->setValue('fornecedor_afiliado_1', $sale->supplier->affiliated_one); //@phpstan-ignore-line
        $template->setValue('fornecedor_telefone_afiliado_1', $sale->supplier->affiliated_one_phone); //@phpstan-ignore-line
        $template->setValue('fornecedor_afiliado_2', $sale->supplier->affiliated_two); //@phpstan-ignore-line
        $template->setValue('fornecedor_telefone_afiliado_2', $sale->supplier->affiliated_two_phone); //@phpstan-ignore-line
        $template->setValue('fornecedor_descricao', $sale->supplier->description); //@phpstan-ignore-line

        //Substitui os placeholders com os dados do endereco do fornecedor
        $template->setValue('fornecedor_cep', $sale->supplier->address->cep); //@phpstan-ignore-line
        $template->setValue('fornecedor_rua', $sale->supplier->address->street); //@phpstan-ignore-line
        $template->setValue('fornecedor_numero', $sale->supplier->address->number); //@phpstan-ignore-line
        $template->setValue('fornecedor_bairro', $sale->supplier->address->neighborhood); //@phpstan-ignore-line
        $template->setValue('fornecedor_cidade', $sale->supplier->address->city->name); //@phpstan-ignore-line
        $template->setValue('fornecedor_estado', $sale->supplier->address->state); //@phpstan-ignore-line
        $template->setValue('fornecedor_complemento', $sale->supplier->address->complement); //@phpstan-ignore-line

        //Substitui os placeholders com os dados da venda
        $template->setValue('metodo_de_pagamento', $sale->payment_method); //@phpstan-ignore-line
        $template->setValue('status', $sale->status); //@phpstan-ignore-line
        $template->setValue('data_venda', Carbon::parse($sale->date_sale)->format('d/m/Y')); //@phpstan-ignore-line
        $template->setValue('data_pagamento', Carbon::parse($sale->date_payment)->format('d/m/Y')); //@phpstan-ignore-line
        $template->setValue('desconto', $sale->discount); //@phpstan-ignore-line
        $template->setValue('acrescimo', $sale->surcharge); //@phpstan-ignore-line
        $template->setValue('entrada', $sale->down_payment); //@phpstan-ignore-line
        $template->setValue('numero_parcelas', $sale->number_installments); //@phpstan-ignore-line
        $template->setValue('reembolso', $sale->reimbursement); //@phpstan-ignore-line
        $template->setValue('data_cancelamento', Carbon::parse($sale->date_cancel)->format('d/m/Y')); //@phpstan-ignore-line
        $template->setValue('total', number_format($sale->total, 2, ',', '.')); //@phpstan-ignore-line

        if ($sale->number_installments > 1) {//@phpstan-ignore-line
            for ($i = 0; $i < $sale->number_installments; $i++) {
                $template->setValue("parcela_{$i}_data_vencimento", Carbon::parse($sale->paymentInstallments[$i]->due_date)->format('d/m/Y')); //@phpstan-ignore-line
                $template->setValue("parcela_{$i}_valor", number_format($sale->paymentInstallments[$i]->value, 2, ',', '.')); //@phpstan-ignore-line
                $template->setValue("parcela_{$i}_status", $sale->paymentInstallments[$i]->status); //@phpstan-ignore-line
                $template->setValue("parcela_{$i}_data_pagamento", Carbon::parse($sale->paymentInstallments[$i]->due_date)->format('d/m/Y')); //@phpstan-ignore-line
                $template->setValue("parcela_{$i}_valor_pagamento", number_format($sale->paymentInstallments[$i]->payment_value, 2, ',', '.')); //@phpstan-ignore-line
                $template->setValue("parcela_{$i}_metodo_pagamento", $sale->paymentInstallments[$i]->payment_method); //@phpstan-ignore-line
                $template->setValue("parcela_{$i}_discount", number_format($sale->paymentInstallments[$i]->discount, 2, ',', '.')); //@phpstan-ignore-line
                $template->setValue("parcela_{$i}_surcharge", number_format($sale->paymentInstallments[$i]->surcharge, 2, ',', '.')); //@phpstan-ignore-line
            }
        }

        // Salva o contrato preenchido
        $caminhoContratoPreenchido = "storage/contracts/contrato_{$sale->client->name}.docx"; //@phpstan-ignore-line
        $template->saveAs($caminhoContratoPreenchido);

        // Retorna uma resposta ou faz o download do arquivo
        return response()->download($caminhoContratoPreenchido)->deleteFileAfterSend(true);
    }
}
