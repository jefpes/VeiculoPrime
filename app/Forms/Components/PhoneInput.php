<?php

namespace App\Forms\Components;

use Filament\Forms\Components\{TextInput};
use Filament\Support\RawJs;

class PhoneInput extends TextInput
{
    protected string $view = 'filament-forms::components.text-input';

    public function setUp(): void
    {
        parent::setUp();

        $this->label('Phone');

        $this->type('tel');

        $this->mask(
            RawJs::make(<<<'JS'
                    $input.replace(/\D/g, '').length === 9 ? '9 9999-9999' : '9999-9999'
                JS)
        );
    }
}
