<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\{Pages};
use App\Models\City;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    public static function getModelLabel(): string
    {
        return __('Cities');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        if ($record->clients->count() > 0 || $record->suppliers->count() > 0 || $record->employees->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title(__('City cannot be deleted'))
                                ->body(__('City is associated with clients, suppliers and employees'))
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->success()
                            ->title(__('City deleted successfully'))
                            ->send();

                        $record->delete();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCities::route('/'),
        ];
    }
}
