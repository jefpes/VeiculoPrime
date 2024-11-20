<?php

namespace App\Helpers;

use Filament\Forms\Components\{Component, Grid, TextInput, Textarea};

class AddressForm
{
    public static function setAddressFields(): Component
    {
        return
        Grid::make()->relationship('address')->columns(['md' => 2, 1])->schema([
            TextInput::make('zip_code')
                ->required()
                ->mask('99999-999'),
            TextInput::make('state')
                ->required()
                ->maxLength(255),
            TextInput::make('city')
                ->required()
                ->maxLength(255),
            TextInput::make('neighborhood')
                ->required()
                ->maxLength(255),
            Grid::make()
                ->columns(5)
                ->schema([
                    TextInput::make('street')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(4),
                    TextInput::make('number')
                        ->minValue(0),
                ]),
            Textarea::make('complement')
                ->maxLength(255)
                ->rows(1)
                ->columnSpanFull(),
        ]);
    }
}
