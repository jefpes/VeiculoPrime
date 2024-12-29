<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StoreResource\{Pages};
use App\Forms\Components\{ZipCode};
use App\Models\Store;
use App\Tools\FormFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Tables};
use Illuminate\Support\Str;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Subdomain')
                            ->live(debounce: 1000, onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state, '_')))
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->rules(['regex:/^[^\s]+$/'])
                            ->maxLength(255),
                        Forms\Components\Grid::make()
                        ->columns(2)
                        ->schema([
                            Forms\Components\Grid::make()->columnSpan(1)->schema([
                                ZipCode::make('zip_code'),
                                Forms\Components\TextInput::make('state')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('neighborhood')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Grid::make()
                                    ->columns(5)
                                    ->schema([
                                        Forms\Components\TextInput::make('street')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(['md' => 4, 'sm' => 5]),
                                        Forms\Components\TextInput::make('number')
                                            ->columnSpan(['md' => 1, 'sm' => 5])
                                            ->minValue(0),
                                    ]),
                                Forms\Components\Textarea::make('complement')
                                    ->maxLength(255)
                                    ->rows(1)
                                    ->columnSpanFull(),
                            ]),
                            Forms\Components\Grid::make()->columnSpan(1)->columns(1)->schema([
                                FormFields::setPhoneFields()->grid(1), //@phpstan-ignore-line
                            ]),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index'  => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit'   => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
