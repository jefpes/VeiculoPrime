<?php

namespace App\Enums;

enum StatusPayments: string
{
    case PG = 'PAGO';
    case PN = 'PENDENTE';
    case CN = 'CANCELADO';
    case RF = 'REEMBOLSADO';
}
