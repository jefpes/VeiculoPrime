<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VehicleTypeResource\{Pages};
use App\Models\VehicleType;
use App\Rules\UniqueWithinTenant;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class VehicleTypeResource extends Resource
{
    protected static ?string $model = VehicleType::class;

    protected static ?int $navigationSort = 8;

    public static function getNavigationGroup(): ?string
    {
        return __('Vehicle');
    }

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
                    ->required()
                    ->maxLength(255)
                    ->rules([
                        fn ($record) => new UniqueWithinTenant(
                            table: 'vehicle_types',
                            column: 'name',
                            ignore: $record?->id
                        ),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth_user()->tenant_id === null)
                    ->sortable(),
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
