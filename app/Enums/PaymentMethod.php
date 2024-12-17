<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case CD = 'CARTÃO DE DÉBITO';
    case CC = 'CARTÃO DE CRÉDITO';
    case DP = 'DEPÓSITO';
    case DN = 'DINHEIRO';
    case TR = 'TRANSFERÊNCIA';
    case PX = 'PIX';
    case CH = 'CHEQUE';
    case CP = 'CREDIÁRIO PRÓPRIO';
    case BB = 'BOLETO BANCÁRIO';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CD => 'Cartão de Débito',
            self::CC => 'Cartão de Crédito',
            self::DP => 'Depósito',
            self::DN => 'Dinheiro',
            self::TR => 'Transferência',
            self::PX => 'PIX',
            self::CH => 'Cheque',
            self::CP => 'Crediário Próprio',
            self::BB => 'Boleto Bancário',
        };
    }
}
