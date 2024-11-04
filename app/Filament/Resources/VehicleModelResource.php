<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleModelResource\{Pages};
use App\Models\Brand;
use App\Models\{VehicleModel, VehicleType};
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class VehicleModelResource extends Resource
{
    protected static ?string $model = VehicleModel::class;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('Vehicle');
    }

    public static function getModelLabel(): string
    {
        return __('Model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Models');
    }

    public static function getLabel(): string
    {
        return __('Model');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->rule('required')
                    ->rule('max:255')
                    ->unique('vehicle_models', 'name', ignoreRecord: true),
                Forms\Components\Select::make('vehicle_type_id')
                    ->relationship('type', 'name')
                    ->rule('required'),
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->rule('required'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand'),
                Tables\Columns\TextColumn::make('type.name')
                    ->label('Type'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(fn (): array => VehicleType::pluck('name', 'id')->toArray()) // @phpstan-ignore-line
                    ->attribute('vehicle_type_id')
                    ->translateLabel(true),
                Tables\Filters\SelectFilter::make('brand')
                    ->options(fn (): array => Brand::pluck('name', 'id')->toArray()) // @phpstan-ignore-line
                    ->attribute('brand_id')
                    ->translateLabel(true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        if ($record->vehicles->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title(__('Model is in use'))
                                ->body(__('Model is in use by vehicles'))
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->success()
                            ->title(__('Model deleted successfully'))
                            ->send();

                        $record->delete();
                    }),
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
            'index' => Pages\ListVehicleModels::route('/'),
            // 'create' => Pages\CreateVehicleModel::route('/create'),
            // 'edit' => Pages\EditVehicleModel::route('/{record}/edit'),
        ];
    }
}
