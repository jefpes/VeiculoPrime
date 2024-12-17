<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SteeringTypes: string implements HasLabel
{
    case ELETRICA   = 'ELÉTRICA';
    case HIDRAULICA = 'HIDRÁULICA';
    case MECANICA   = 'MECÂNICA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ELETRICA   => 'Elétrica',
            self::HIDRAULICA => 'Hidráulica',
            self::MECANICA   => 'Mecânica',
        };
    }
}
