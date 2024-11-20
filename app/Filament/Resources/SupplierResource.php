<?php

namespace App\Filament\Resources;

use App\Enums\PersonType;
use App\Enums\{Genders, MaritalStatus};
use App\Filament\Resources\SupplierResource\RelationManagers\PhotosRelationManager;
use App\Filament\Resources\SupplierResource\{Pages};
use App\Forms\Components\PhoneInput;
use App\Helpers\AddressForm;
use App\Models\{Supplier};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?int $navigationSort = 7;

    public static function getNavigationGroup(): ?string
    {
        return __('People');
    }

    public static function getModelLabel(): string
    {
        return __('Supplier');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Suppliers');
    }

    public static function form(Form $form): Form
    {

        return $form->schema([
            Forms\Components\Tabs::make('tabs')->columnSpanFull()->tabs([
                Forms\Components\Tabs\Tab::make('Dados Pessoais')->schema([
                    Forms\Components\TextInput::make('name')
                        ->maxLength(255),
                    Forms\Components\ToggleButtons::make('supplier_type')
                        ->rule('required')
                        ->inline()
                        ->label('Tipo de Pessoa')
                        ->options(PersonType::class)
                        ->live(),
                    Forms\Components\Select::make('gender')
                        ->visible(fn (Forms\Get $get): bool => $get('supplier_type') === 'Física')
                        ->options(Genders::class),
                    Forms\Components\TextInput::make('supplier_id')
                            ->label(fn (Forms\Get $get): string => match ($get('supplier_type')) {
                                'Física'   => 'CPF',
                                'Jurídica' => 'CNPJ',
                                default    => 'CPF',
                            })
                        ->required()
                        ->mask(fn (Forms\Get $get): string => match ($get('supplier_type')) {
                            'Física'   => '999.999.999-99',
                            'Jurídica' => '99.999.999/9999-99',
                            default    => '999.999.999-99',
                        })
                        ->length(fn (Forms\Get $get): int => match ($get('supplier_type')) {
                            'Física'   => 14,
                            'Jurídica' => 18,
                            default    => 14,
                        }),
                    Forms\Components\TextInput::make('rg')
                        ->label('RG')
                        ->visible(fn (Forms\Get $get): bool => $get('supplier_type') === 'Física')
                        ->mask('99999999999999999999')
                        ->maxLength(20),
                    Forms\Components\Select::make('marital_status')
                        ->label('Marital Status')
                        ->visible(fn (Forms\Get $get): bool => $get('supplier_type') === 'Física')
                        ->options(collect(MaritalStatus::cases())->mapWithKeys(fn (MaritalStatus $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    PhoneInput::make('phone_one')
                        ->label('Phone (1)')
                        ->required(),
                    PhoneInput::make('phone_two')
                        ->label('Phone (2)'),
                    Forms\Components\DatePicker::make('birth_date')
                        ->visible(fn (Forms\Get $get): bool => $get('supplier_type') === 'Física')
                        ->label('Birth Date')
                        ->required(fn (Forms\Get $get): bool => $get('supplier_type') === 'Física'),
                    Forms\Components\Textarea::make('description')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),
                Forms\Components\Tabs\Tab::make('Endereço')->schema([
                    AddressForm::setAddressFields(),
                ]),
                Forms\Components\Tabs\Tab::make(__('Affiliates'))->schema([
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
            ]),
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
                        Tables\Columns\TextColumn::make('supplier_id')
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
                        Tables\Columns\TextColumn::make('rg')
                            ->label('RG')
                            ->searchable()
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('birth_date')
                            ->date('d/m/Y')
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('father')
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('mother')
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('marital_status')
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('spouse')
                            ->toggleable(isToggledHiddenByDefault: true),
                    ])
                    ->filters([
                        //
                    ])
                    ->actions([
                        Tables\Actions\ViewAction::make(),
                        Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit'   => Pages\EditSupplier::route('/{record}/edit'),
            'view'   => Pages\ViewSupplier::route('/{record}'),
        ];
    }
}
