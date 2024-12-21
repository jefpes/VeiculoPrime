<?php

namespace App\Filament\Admin\Resources\PeopleResource\RelationManagers;

use App\Forms\Components\MoneyInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class EmployeeRelationManager extends RelationManager
{
    protected static string $relationship = 'employee';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                MoneyInput::make('salary')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('admission_date')
                    ->required(),
                Forms\Components\DatePicker::make('resignation_date')
                    ->readOnly(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('salary')->money('BRL'),
                Tables\Columns\TextColumn::make('admission_date')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('resignation_date')->date('d/m/Y'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
