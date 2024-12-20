<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum StatusPayments: string implements HasLabel
{
    case PG = 'PAGO';
    case PN = 'PENDENTE';
    case CN = 'CANCELADO';
    case RF = 'REEMBOLSADO';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PG => 'Pago',
            self::PN => 'Pendente',
            self::CN => 'Cancelado',
            self::RF => 'Reembolsado',
        };
    }
}
