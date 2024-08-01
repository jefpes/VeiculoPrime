<?php

namespace App\Enums;

enum PaymentMethod: string
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
}
