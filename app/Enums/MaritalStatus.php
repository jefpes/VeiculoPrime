<?php

namespace App\Enums;

enum MaritalStatus: string
{
    case SOLTEIRO      = 'Solteiro (a)';
    case CASADO        = 'Casado (a)';
    case DIVORCIADO    = 'Divorciado (a)';
    case VIUVO         = 'Viúvo (a)';
    case UNIAO_ESTAVEL = 'União Estável';
    case SEPARADO      = 'Separado (a)';
}
