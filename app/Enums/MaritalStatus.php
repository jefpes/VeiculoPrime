<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MaritalStatus: string implements HasLabel
{
    case SOLTEIRO      = 'Solteiro';
    case CASADO        = 'Casado';
    case DIVORCIADO    = 'Divorciado';
    case VIUVO         = 'Viúvo';
    case UNIAO_ESTAVEL = 'União Estável';
    case SEPARADO      = 'Separado';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SOLTEIRO      => 'Solteiro',
            self::CASADO        => 'Casado',
            self::DIVORCIADO    => 'Divorciado',
            self::VIUVO         => 'Viúvo',
            self::UNIAO_ESTAVEL => 'União Estável',
            self::SEPARADO      => 'Separado',
        };
    }
}
