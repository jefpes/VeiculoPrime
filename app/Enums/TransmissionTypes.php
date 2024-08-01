<?php

namespace App\Enums;

enum TransmissionTypes: string
{
    case AUTOMATICO     = 'AUTOMÁTICA';
    case MANUAL         = 'MANUAL';
    case SEMIAUTOMATICO = 'SEMIAUTOMÁTICA';
}
