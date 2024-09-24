<?php

namespace App\Enums;

use Filament\Support\Contracts\{HasIcon, HasLabel};

enum TaxpayerType: string implements HasLabel, HasIcon
{
    case FISICA   = 'Física';
    case JURIDICA = 'Jurídica';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FISICA   => 'Física',
            self::JURIDICA => 'Jurídica',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::FISICA   => 'heroicon-o-user',
            self::JURIDICA => 'heroicon-o-building-office',
        };
    }
}
