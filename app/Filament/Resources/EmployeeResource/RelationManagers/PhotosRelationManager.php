<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class PhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'photos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('path')
                    ->label('Foto')
                    ->required()
                    ->directory('employee_photos')
                    ->image(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->modelLabel(__('Photo'))
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Photo')
                    ->size(250),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Adicionar Foto')->modalHeading('Adicionar Foto'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
