<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\{Pages};
use App\Models\Brand;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('Vehicle');
    }

    public static function getModelLabel(): string
    {
        return __('Brand');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Brands');
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
                                ->title(__('Brand is in use'))
                                ->body(__('Brand is in use by vehicles.'))
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->success()
                            ->title(__('Brand deleted successfully'))
                            ->body(__('Brand has been deleted.'))
                            ->send();

                        $record->delete();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBrands::route('/'),
        ];
    }
}
