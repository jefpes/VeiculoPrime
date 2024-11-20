<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\{TextInput};

class ZipCode extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this->mask('99999-999')
            ->minLength(9)
            ->suffixAction(
                Action::make('search')
                    ->icon('heroicon-o-search')
                    ->label('Buscar')
                    ->button()
            );
    }
}
