<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\{Pages};
use App\Models\Vehicle;
use Filament\Forms\Components\{DatePicker, Section, Select, TextInput};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Tables};
use Illuminate\Database\Eloquent\{Builder};

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Vehicle');
    }

    public static function getModelLabel(): string
    {
        return __('Vehicles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    DatePicker::make('purchase_date')
                        ->required(),
                    TextInput::make('fipe_price')
                        ->prefix('R$')
                        ->numeric(),
                    TextInput::make('purchase_price')
                        ->prefix('R$')
                        ->required()
                        ->numeric(),
                    TextInput::make('sale_price')
                        ->prefix('R$')
                        ->required()
                        ->numeric(),
                    TextInput::make('promotional_price')
                        ->prefix('R$')
                        ->numeric(),
                    Select::make('vehicle_model_id')
                        ->relationship('model', 'name'),
                    Select::make('supplier_id')
                        ->relationship('supplier', 'name'),
                    TextInput::make('year_one')
                        ->required(),
                    TextInput::make('year_two')
                        ->required(),
                    TextInput::make('km')
                        ->required()
                        ->numeric(),
                    TextInput::make('fuel')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('engine_power')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('steering')
                        ->maxLength(255),
                    TextInput::make('transmission')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('doors')
                        ->maxLength(255),
                    TextInput::make('seats')
                        ->maxLength(255),
                    TextInput::make('traction')
                        ->maxLength(255),
                    TextInput::make('color')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('plate')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('chassi')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('renavam')
                        ->required()
                        ->maxLength(255),
                    DatePicker::make('sold_date')->disabled(),
                    TextInput::make('description')
                        ->maxLength(255),
                    TextInput::make('annotation')
                        ->maxLength(255),
                ])->columns(4),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plate')
                    ->searchable(),
                TextColumn::make('purchase_date')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('fipe_price')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('BRL'),
                TextColumn::make('purchase_price')
                    ->sortable()
                    ->money('BRL'),
                TextColumn::make('sale_price')
                    ->sortable()
                    ->money('BRL'),
                TextColumn::make('promotional_price')
                    ->sortable()
                    ->money('BRL'),
                TextColumn::make('model.name')
                    ->sortable(),
                TextColumn::make('supplier.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('combined_years')
                    ->label('Year'),
                TextColumn::make('km')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fuel')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('engine_power')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('steering')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('transmission')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('doors')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('seats')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('traction')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('color')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('chassi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('renavam')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sold_date')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('annotation')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('purchase_date')
                ->form([
                    DatePicker::make('purchase_date_initial')
                        ->label('Purchase Date After'),
                    DatePicker::make('purchase_date_final')
                        ->label('Purchase Date Before'),
                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['purchase_date_initial'], fn ($query, $value) => $query->where('purchase_date', '>=', $value))
                        ->when($data['purchase_date_final'], fn ($query, $value) => $query->where('purchase_date', '<=', $value));
                }),
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
            'index'  => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view'   => Pages\ViewVehicle::route('/{record}'),
            'edit'   => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
