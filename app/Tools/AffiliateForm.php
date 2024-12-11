<?php

namespace App\Tools;

use App\Forms\Components\{PhoneInput};
use Filament\Forms\Components\{Component, Grid, Repeater, TextInput};

class AffiliateForm
{
    public static function setFields(): Component
    {
        return Repeater::make('affiliates')
            ->grid(2)
            ->hiddenLabel()
            ->addActionLabel(__('Add Affiliate'))
            ->relationship('affiliates')
            ->schema([
                Grid::make()->schema([
                    TextInput::make('type')
                        ->required()
                        ->columnSpan(['md' => 1, 'sm' => 5])
                        ->maxLength(255),
                    TextInput::make('name')
                        ->required()
                        ->columnSpan(['md' => 2, 'sm' => 5])
                        ->maxLength(255),
                    PhoneInput::make('phone')
                        ->columnSpan(['md' => 2, 'sm' => 5])
                        ->required(),
                ])->columns(5),
            ]);
    }
}
