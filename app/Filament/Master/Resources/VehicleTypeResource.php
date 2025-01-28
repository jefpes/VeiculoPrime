<?php

namespace App\Filament\Master\Resources;

use App\Filament\Master\Resources\VehicleTypeResource\{Pages};
use App\Models\VehicleType;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class VehicleTypeResource extends Resource
{
    protected static ?string $model = VehicleType::class;

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $isScopedToTenant = false;

    public static function getModelLabel(): string
    {
        return __('Type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Types');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->action(function ($record) {
                    if ($record->models->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title(__('Type is in use'))
                            ->body(__('Type is in use by vehicles'))
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title(__('Type deleted successfully'))
                        ->send();

                    $record->delete();
                }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVehicleTypes::route('/'),
        ];
    }
}
