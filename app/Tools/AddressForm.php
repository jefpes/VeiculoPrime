<?php

namespace App\Tools;

use App\Forms\Components\ZipCode;
use Filament\Forms\Components\{Component, Grid, Repeater, TextInput, Textarea};

class AddressForm
{
    public static function setAddressFields(): Component
    {
        return Repeater::make('addresses')
                ->grid(2)
                ->hiddenLabel()
                ->addActionLabel(__('Add Address'))
                ->relationship('addresses')
                ->schema([
                    Grid::make()->columns(['md' => 2, 1])->schema([
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
                                    ->columnSpan(['md' => 4, 'sm' => 5]),
                                TextInput::make('number')
                                    ->columnSpan(['md' => 1, 'sm' => 5])
                                    ->minValue(0),
                            ]),
                        Textarea::make('complement')
                            ->maxLength(255)
                            ->rows(1)
                            ->columnSpanFull(),
                    ]),
                ]);
    }
}
