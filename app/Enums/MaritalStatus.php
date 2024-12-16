<?php

namespace App\Enums;

enum MaritalStatus: string
{
    case SOLTEIRO      = 'Solteiro';
    case CASADO        = 'Casado';
    case DIVORCIADO    = 'Divorciado';
    case VIUVO         = 'Viúvo';
    case UNIAO_ESTAVEL = 'União Estável';
    case SEPARADO      = 'Separado';
}
