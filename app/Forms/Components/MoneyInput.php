<?php

namespace App\Forms\Components;

use Filament\Forms\Components\{TextInput};

class MoneyInput extends TextInput
{
    protected string $view = 'filament-forms::components.text-input';

    public function setUp(): void
    {
        parent::setUp();

        $this->prefix('R$')
            ->minValue(0)
            ->extraAlpineAttributes([
                'x-mask:dynamic' => '$money($input, ".", "")',
            ])->extraInputAttributes([
                'style' => 'letter-spacing: 0.2em;',
            ]);
    }
}
