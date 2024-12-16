<?php

namespace App\Filament\Admin\Resources;

use App\Enums\{Genders, MaritalStatus, PersonType};
use App\Filament\Admin\Resources\PeopleResource\{Pages};
use App\Forms\Components\{MoneyInput};
use App\Models\{Employee, People};
use App\Tools\{FormFields, PhotosRelationManager};
use Filament\Forms\Components\{Grid};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class PeopleResource extends Resource
{
    protected static ?string $model = People::class;

    protected static ?int $navigationSort = 6;

    public static function getModelLabel(): string
    {
        return __('Person');
    }

    public static function getPluralModelLabel(): string
    {
        return __('People');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('tabs')->columnSpanFull()->tabs([
                    Forms\Components\Tabs\Tab::make('Dados Pessoais')->schema([
                        Grid::make()->columns(['sm' => 1, 'md' => 2, 'lg' => 3])->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Select::make('gender')
                                ->required()
                                ->options(Genders::class),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->maxLength(255),
                            MoneyInput::make('salary')
                                ->required()
                                ->numeric(),
                            Forms\Components\ToggleButtons::make('person_type')
                                ->rule('required')
                                ->inline()
                                ->label('Tipo de Pessoa')
                                ->options(PersonType::class)
                                ->live(),
                            Forms\Components\TextInput::make('person_id')
                                ->required()
                                ->label(fn (Forms\Get $get): string => match ($get('person_type')) {
                                    'Física'   => 'CPF',
                                    'Jurídica' => 'CNPJ',
                                    default    => 'CPF',
                                })
                            ->mask(fn (Forms\Get $get): string => match ($get('person_type')) {
                                'Física'   => '999.999.999-99',
                                'Jurídica' => '99.999.999/9999-99',
                                default    => '999.999.999-99',
                            })
                            ->length(fn (Forms\Get $get): int => match ($get('person_type')) {
                                'Física'   => 14,
                                'Jurídica' => 18,
                                default    => 14,
                            }),
                            Forms\Components\TextInput::make('rg')
                                ->label('RG')
                                ->visible(fn (Forms\Get $get): bool => $get('person_type') === 'Física')
                                ->mask('99999999999999999999')
                                ->maxLength(20),
                            Forms\Components\DatePicker::make('birth_date')
                                ->required(),
                            Forms\Components\TextInput::make('father')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('mother')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Select::make('marital_status')
                                ->required()
                                ->options(
                                    collect(MaritalStatus::cases())
                                    ->mapWithKeys(fn (MaritalStatus $type) => [$type->value => ucfirst($type->value)])
                                ->toArray()
                                ),
                            Forms\Components\TextInput::make('spouse')
                                ->maxLength(255),
                            Forms\Components\DatePicker::make('admission_date')->required(),
                            Forms\Components\DatePicker::make('resignation_date')->readOnly(),
                        ]),
                    ]),
                    Forms\Components\Tabs\Tab::make(__('Address'))->schema([
                        FormFields::setAddressFields(),
                    ]),
                    Forms\Components\Tabs\Tab::make(__('Phones'))->schema([
                        FormFields::setPhoneFields(),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth_user()->tenant_id === null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string|array => match ($state) {
                        'MASCULINO' => 'info',
                        'FEMININO'  => Color::hex('#ff00b2'),
                        default     => 'success',
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phones.full_phone')
                    ->searchable()
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('rg')
                    ->label('RG')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('admission_date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('resignation_date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('dismiss')
                //     ->label('Dismiss')
                //     ->icon('heroicon-o-arrow-left-start-on-rectangle')
                //     ->color('danger')
                //     ->authorize('delete')
                //     ->authorize(function (People $people) {
                //         return $people->employee->resignation_date === null;
                //     })
                //     ->requiresConfirmation()
                //     ->form([
                //         Forms\Components\DatePicker::make('resignation_date')
                //             ->label('Resignation Date'),
                //     ])
                //     ->action(function (People $people, array $data) {
                //         if ($people->user() !== null) {
                //             $people->user()->delete();
                //         }
                //         $people->employee->update(['resignation_date' => ($data['resignation_date'] ?? now())]);
                //     }),
                // Tables\Actions\Action::make('rehire')
                //     ->label('Rehire')
                //     ->icon('heroicon-o-arrow-left-end-on-rectangle')
                //     ->color('warning')
                //     ->authorize('delete')
                //     ->authorize(function (People $people) {
                //         return $people->employee->resignation_date !== null;
                //     })
                //     ->requiresConfirmation()
                //     ->form([
                //         Forms\Components\DatePicker::make('admission_date')
                //             ->label('Admission Date'),
                //     ])
                //     ->action(function (People $people, array $data) {
                //         if ($people->user() !== null) {
                //             $people->user()->restore();
                //         }

                //         if ($data['admission_date'] === null) {
                //             $people->employee->update(['resignation_date' => null]);

                //             return;
                //         }
                //         $people->employee->update(['resignation_date' => null, 'admission_date' => ($data['admission_date'] ?? now())]);
                //     }),
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
            'index'  => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit'   => Pages\EditEmployee::route('/{record}/edit'),
            'view'   => Pages\ViewEmployee::route('/{record}'),
        ];
    }
}
