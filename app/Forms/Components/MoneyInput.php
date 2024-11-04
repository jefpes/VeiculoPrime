<?php

namespace App\Forms\Components;

use Filament\Forms\Components\{TextInput};

class MoneyInput extends TextInput
{
    protected string $view = 'filament-forms::components.text-input';

    public function setUp(): void
    {
        parent::setUp();

        $this->prefix('R$');
        $this->type('number');
        $this->step('0.01');
        $this->minValue(0);
    }
}
