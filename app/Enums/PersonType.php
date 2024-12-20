<?php

namespace App\Enums;

use Filament\Support\Contracts\{HasColor, HasIcon, HasLabel};

enum PersonType: string implements HasLabel, HasIcon, HasColor
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

    public function getColor(): ?string
    {
        return match ($this) {
            self::FISICA   => 'info',
            self::JURIDICA => 'primary',
        };
    }
}
