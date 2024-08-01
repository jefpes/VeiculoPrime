<?php

namespace App\Filament\Resources;

use App\Enums\{FuelTypes, SteeringTypes, TransmissionTypes};
use App\Filament\Resources\VehicleResource\RelationManagers\PhotosRelationManager;
use App\Filament\Resources\VehicleResource\{Pages};
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Forms\Components\{DatePicker, Section, Select, TextInput, Textarea};
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
                        ->relationship('model', 'name')
                        ->columnSpan(2),
                    Select::make('supplier_id')
                        ->relationship('supplier', 'name')
                        ->columnSpan(3),
                    TextInput::make('year_one')
                        ->required()
                        ->label('Year'),
                    TextInput::make('year_two')
                        ->required()
                        ->label('Year (Model)'),
                    TextInput::make('km')
                        ->required()
                        ->numeric(),
                    Select::make('fuel')
                        ->options(collect(FuelTypes::cases())->mapWithKeys(fn (FuelTypes $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    TextInput::make('engine_power')
                        ->required()
                        ->maxLength(255),
                    Select::make('steering')
                        ->options(collect(SteeringTypes::cases())->mapWithKeys(fn (SteeringTypes $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    Select::make('transmission')
                        ->options(collect(TransmissionTypes::cases())->mapWithKeys(fn (TransmissionTypes $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    TextInput::make('doors')
                        ->numeric(),
                    TextInput::make('seats')
                        ->numeric(),
                    TextInput::make('traction')
                        ->maxLength(255),
                    TextInput::make('color')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('plate')
                        ->required()
                        ->maxLength(255)
                        ->mask('aaa-9*99'),
                    TextInput::make('chassi')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('renavam')
                        ->required()
                        ->maxLength(255),
                    DatePicker::make('sold_date')->disabled(),
                    Textarea::make('description')
                        ->maxLength(255)->columnStart(1)->columnSpanFull(),
                    Textarea::make('annotation')
                        ->maxLength(255)->columnStart(1)->columnSpanFull(),
                ])->columns(7),
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
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['purchase_date_initial'] ?? null) {
                        $indicators[] = __('Purchase Date After: ') . Carbon::parse($data['purchase_date_initial'])->format('d/m/Y');
                    }

                    if ($data['purchase_date_final'] ?? null) {
                        $indicators[] = __('Purchase Date Before: ') . Carbon::parse($data['purchase_date_final'])->format('d/m/Y');
                    }

                    return $indicators;
                }),
                Filter::make('year_one')
                ->form([
                    Select::make('year_one')
                        ->label('After Year')
                        ->options(function () {
                            return Vehicle::query()
                                ->select('year_one')
                                ->distinct()
                                ->orderBy('year_one')
                                ->pluck('year_one', 'year_one')
                                ->toArray();
                        }),
                ])
                ->query(
                    fn (Builder $query, array $data) => $query->when($data['year_one'], fn ($query, $value) => $query->where('year_one', '>=', $value))
                )->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['year_one'] ?? null) {
                        $indicators[] = __('After Year') . ': ' . $data['year_one'];
                    }

                    return $indicators;
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
            PhotosRelationManager::class,
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
