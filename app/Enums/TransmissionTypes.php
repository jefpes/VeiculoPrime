<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TransmissionTypes: string implements HasLabel
{
    case AUTOMATICO     = 'AUTOMÁTICA';
    case MANUAL         = 'MANUAL';
    case SEMIAUTOMATICO = 'SEMIAUTOMÁTICA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AUTOMATICO     => 'Automática',
            self::MANUAL         => 'Manual',
            self::SEMIAUTOMATICO => 'Semi-automática',
        };
    }
}
