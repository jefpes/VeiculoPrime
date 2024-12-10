<?php

namespace App\Helpers;

use App\Forms\Components\ZipCode;
use Filament\Forms\Components\{Component, Grid, TextInput, Textarea};

class AddressForm
{
    public static function setAddressFields(): Component
    {
        return Grid::make()->columns(['md' => 2, 1])->schema([
            ZipCode::make('zip_code'),
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
