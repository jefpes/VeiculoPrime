<?php

namespace App\Enums;

use Filament\Support\Contracts\{HasIcon, HasLabel};

enum Sexes: string implements HasLabel, HasIcon
{
    case MASCULINO = 'MASCULINO';
    case FEMININO  = 'FEMININO';
    case OUTRO     = 'OUTRO';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MASCULINO => 'MASCULINO',
            self::FEMININO  => 'FEMININO',
            self::OUTRO     => 'OUTRO',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::MASCULINO => 'icon-man',
            self::FEMININO  => 'icon-woman',
            self::OUTRO     => 'Jurídica',
        };
    }
}
