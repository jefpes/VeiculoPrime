<?php

namespace App\Forms\Components;

use Filament\Forms\Components\{TextInput};

class PhoneInput extends TextInput
{
    protected string $view = 'filament-forms::components.text-input';

    public function setUp(): void
    {
        parent::setUp();

        $this->prefixIcon('heroicon-s-phone');
        $this->mask('(99) 99999-9999');
    }
}
