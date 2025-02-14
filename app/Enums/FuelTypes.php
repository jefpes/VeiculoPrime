<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum FuelTypes: string implements HasLabel
{
    case GASOLINE = 'GASOLINA';
    case DIESEL   = 'DIESEL';
    case ETHANOL  = 'ETANOL';
    case FLEX     = 'FLEX';
    case HYBRID   = 'HIBRIDO';
    case ELECTRIC = 'ELETRICO';
    case OTHER    = 'OUTRO';

    public function getLabel(): string
    {
        return match ($this) {
            self::GASOLINE => 'Gasolina',
            self::DIESEL   => 'Diesel',
            self::ETHANOL  => 'Etanol',
            self::FLEX     => 'Flex',
            self::HYBRID   => 'Híbrido',
            self::ELECTRIC => 'Elétrico',
            self::OTHER    => 'Outro',
        };
    }
}
