<?php

namespace App\Filament\Resources;

use App\Enums\TaxpayerType;
use App\Enums\{Genders, MaritalStatus, States};
use App\Filament\Resources\ClientResource\RelationManagers\{PhotosRelationManager};
use App\Filament\Resources\ClientResource\{Pages};
use App\Forms\Components\PhoneInput;
use App\Models\Client;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    public static function getModelLabel(): string
    {
        return __('Client');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Clients');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados Pessoais')->schema([
                    Forms\Components\TextInput::make('name')
                        ->maxLength(255),
                    Forms\Components\Select::make('gender')
                        ->visible(fn (Forms\Get $get): bool => $get('client_type') === 'Física')
                        ->options(Genders::class),
                    Forms\Components\ToggleButtons::make('client_type')
                        ->rule('required')
                        ->inline()
                        ->label('Tipo de Pessoa')
                        ->options(TaxpayerType::class)
                        ->live(),
                    Forms\Components\TextInput::make('client_id')
                            ->label(fn (Forms\Get $get): string => match ($get('client_type')) {
                                'Física'   => 'CPF',
                                'Jurídica' => 'CNPJ',
                                default    => 'CPF',
                            })
                        ->required()
                        ->mask(fn (Forms\Get $get): string => match ($get('client_type')) {
                            'Física'   => '999.999.999-99',
                            'Jurídica' => '99.999.999/9999-99',
                            default    => '999.999.999-99',
                        })
                        ->length(fn (Forms\Get $get): int => match ($get('client_type')) {
                            'Física'   => 14,
                            'Jurídica' => 18,
                            default    => 14,
                        }),
                    Forms\Components\TextInput::make('rg')
                        ->label('RG')
                        ->visible(fn (Forms\Get $get): bool => $get('client_type') === 'Física')
                        ->mask('99999999999999999999')
                        ->maxLength(20),
                    Forms\Components\Select::make('marital_status')
                        ->label('Marital Status')
                        ->visible(fn (Forms\Get $get): bool => $get('client_type') === 'Física')
                        ->options(collect(MaritalStatus::cases())->mapWithKeys(fn (MaritalStatus $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    PhoneInput::make('phone_one')
                        ->label('Phone (1)')
                        ->required(),
                    PhoneInput::make('phone_two')
                        ->label('Phone (2)'),
                    Forms\Components\DatePicker::make('birth_date')
                        ->visible(fn (Forms\Get $get): bool => $get('client_type') === 'Física')
                        ->label('Birth Date')
                        ->required(fn (Forms\Get $get): bool => $get('client_type') === 'Física'),
                    Forms\Components\Textarea::make('description')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])->columns(['sm' => 1, 'md' => 2, 'lg' => 3]),

                Section::make(__('Affiliates'))->schema([
                    Forms\Components\TextInput::make('father')
                        ->maxLength(255),
                    PhoneInput::make('father_phone')
                        ->label('Father Phone'),
                    Forms\Components\TextInput::make('mother')
                        ->maxLength(255),
                    PhoneInput::make('mother_phone')
                        ->label('Mother Phone'),
                    Forms\Components\TextInput::make('affiliated_one')
                        ->label('Affiliated (1)')
                        ->maxLength(255),
                    PhoneInput::make('affiliated_one_phone')
                        ->label('Affiliated Phone (1)'),
                    Forms\Components\TextInput::make('affiliated_two')
                        ->label('Affiliated (2)')
                        ->maxLength(255),
                    PhoneInput::make('affiliated_two_phone')
                        ->label('Affiliated Phone (2)'),
                ])->columns(['sm' => 1, 'md' => 2]),

                Section::make(__('Address'))->relationship('address')->schema([
                    Forms\Components\TextInput::make('zip_code')
                        ->required()
                        ->mask('99999-999'),
                    Forms\Components\TextInput::make('street')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('number')
                        ->numeric()
                        ->minValue(0),
                    Forms\Components\TextInput::make('complement')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('neighborhood')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('city_id')
                        ->relationship('city', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('state')
                        ->options(collect(States::cases())->mapWithKeys(fn (States $status) => [
                            $status->value => $status->value,
                        ])->toArray())
                        ->required()
                        ->searchable()
                        ->preload(),
                ])->columns(['sm' => 1, 'md' => 3, 'lg' => 4]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modelLabel('Cliente')
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
                Tables\Columns\TextColumn::make('client_id')
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
            PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit'   => Pages\EditClient::route('/{record}/edit'),
            'view'   => Pages\ViewClient::route('/{record}'),
        ];
    }
}
