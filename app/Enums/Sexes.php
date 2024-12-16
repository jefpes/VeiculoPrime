<?php

namespace App\Enums;

use Filament\Support\Contracts\{HasColor, HasLabel};

enum Sexes: string implements HasLabel, HasColor
{
    case MASCULINO = 'Masculino';
    case FEMININO  = 'Feminino';
    case OUTRO     = 'Outro';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MASCULINO => 'Masculino',
            self::FEMININO  => 'Feminino',
            self::OUTRO     => 'Outro',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::MASCULINO => 'info',
            self::FEMININO  => 'pink',
            self::OUTRO     => 'warning',
        };
    }
}
