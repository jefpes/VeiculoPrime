<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\{Pages};
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\{Tables};

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
                    ->columns([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('gender')
                            ->badge()
                            ->color(fn (string $state): string|array => match ($state) {
                                'MASCULINO' => 'info',
                                'FEMININO'  => Color::hex('#ff00b2'),
                                default     => 'success',
                            })
                            ->searchable(),
                        Tables\Columns\TextColumn::make('taxpayer_id')
                            ->label('CPF/CNPJ')
                            ->copyable()
                            ->searchable(),
                        Tables\Columns\TextColumn::make('phones')
                            ->getStateUsing(function ($record) {
                                if ($record->phone_two !== null) {
                                    return  $record->phone_one . ' | ' . $record->phone_two;
                                }

                                return  $record->phone_one;
                            })
                            ->label('Phone'),
                    ])
                    ->filters([
                        //
                    ])
                    ->actions([
                        Tables\Actions\ViewAction::make(),
                        Tables\Actions\EditAction::make(),
                    ])
                    ->bulkActions([
                        Tables\Actions\BulkActionGroup::make([
                            Tables\Actions\DeleteBulkAction::make(),
                        ]),
                    ]);

    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit'   => Pages\EditSupplier::route('/{record}/edit'),
            'view'   => Pages\ViewSupplier::route('/{record}'),
        ];
    }
}
