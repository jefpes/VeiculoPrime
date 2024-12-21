<?php

namespace App\Filament\Admin\Resources;

use App\Enums\{MaritalStatus, Permission, PersonType, Sexes};
use App\Filament\Admin\Resources\PeopleResource\RelationManagers\EmployeeRelationManager;
use App\Filament\Admin\Resources\PeopleResource\{Pages};
use App\Models\{People};
use App\Tools\{FormFields, PhotosRelationManager};
use Filament\Forms\Components\{Livewire};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class PeopleResource extends Resource
{
    protected static ?string $model = People::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

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
            ->columns(1)
            ->schema([
                Forms\Components\Tabs::make('tabs')->tabs([
                    Forms\Components\Tabs\Tab::make('Dados Pessoais')->schema([
                        Forms\Components\Grid::make()->columns(['sm' => 1, 'md' => 2, 'lg' => 3])->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->maxLength(255)
                                ->rules(['email', unique_within_tenant_rule(static::$model)]),
                            Forms\Components\ToggleButtons::make('person_type')
                                ->rule('required')
                                ->inline()
                                ->label('Tipo de Pessoa')
                                ->options(PersonType::class)
                                ->live(),
                            Forms\Components\TextInput::make('person_id')
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
                                })
                                ->rules([unique_within_tenant_rule(static::$model)]),
                            Forms\Components\Select::make('sex')
                                ->visible(fn (Forms\Get $get): bool => $get('person_type') === 'Física')
                                ->options(Sexes::class),
                            Forms\Components\TextInput::make('rg')
                                ->label('RG')
                                ->rules([unique_within_tenant_rule(static::$model)])
                                ->visible(fn (Forms\Get $get): bool => $get('person_type') === 'Física')
                                ->mask('99999999999999999999')
                                ->maxLength(20),
                            Forms\Components\DatePicker::make('birthday')
                                ->visible(fn (Forms\Get $get): bool => $get('person_type') === 'Física'),
                            Forms\Components\TextInput::make('father')
                                ->visible(fn (Forms\Get $get): bool => $get('person_type') === 'Física')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('mother')
                                ->visible(fn (Forms\Get $get): bool => $get('person_type') === 'Física')
                                ->maxLength(255),
                            Forms\Components\Select::make('marital_status')
                                ->visible(fn (Forms\Get $get): bool => $get('person_type') === 'Física')
                                ->options(MaritalStatus::class),
                            Forms\Components\TextInput::make('spouse')
                                ->visible(fn (Forms\Get $get): bool => $get('person_type') === 'Física')
                                ->maxLength(255),
                            Forms\Components\Grid::make()->columnSpan(1)->columns(2)->schema([
                                Forms\Components\ToggleButtons::make('client')
                                    ->inline()
                                    ->label('Client Active')
                                    ->options([0 => 'Não', 1 => 'Sim']),
                                Forms\Components\ToggleButtons::make('supplier')
                                    ->inline()
                                    ->label('Supplier Active')
                                    ->options([0 => 'Não', 1 => 'Sim']),
                            ]),

                        ]),
                    ]),
                    Forms\Components\Tabs\Tab::make(__('Address'))->schema([
                        FormFields::setAddressFields(),
                    ]),
                    Forms\Components\Tabs\Tab::make(__('Phones'))->schema([
                        FormFields::setPhoneFields(),
                    ]),
                    Forms\Components\Tabs\Tab::make(__('Employment contract'))->schema(
                        function ($livewire) {
                            return [
                                Livewire::make(EmployeeRelationManager::class, ['pageClass' => static::class, 'ownerRecord' => $livewire->record, 'lazy' => true]),
                            ];
                        }
                    ),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->modifyQueryUsing(function ($query) {
                return $query->with(['tenant', 'user', 'phones', 'employee']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth_user()->tenant_id === null)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->description(fn ($record): string|null => $record->user?->email)
                    ->label('User')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sex')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('person_type')
                    ->label('Type')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('apart')
                    ->label('Roles')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $roles = [];

                        if ($record->client) {
                            $roles[] = __('Client');
                        }

                        if ($record->employee->isNotEmpty() && $record->employee->last()->resignation_date === null) {
                            $roles[] = __('Employee');
                        }

                        if ($record->supplier) {
                            $roles[] = __('Supplier');
                        }

                        return $roles;
                    })
                    ->colors([
                        'primary' => __('Client'),
                        'success' => __('Employee'),
                        'warning' => __('Supplier'),
                    ]),
                Tables\Columns\TextColumn::make('person_id')
                    ->label('CPF/CNPJ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rg')
                    ->label('RG')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phones.full_phone')
                    ->searchable()
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('birthday')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('attach_user')
                    ->label('User')
                    ->authorize('attach-user')
                    ->icon('heroicon-o-user')
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name', modifyQueryUsing: function ($query, $record) {
                                if ($record->user !== null) {
                                    return $query->whereDoesntHave('people')->orWhere('id', $record->user->id);
                                }

                                return $query->whereDoesntHave('people');
                            }),
                    ])
                    ->fillForm(fn ($record) => ['user_id' => $record->user_id])
                    ->action(function ($record, array $data) {
                        $record->update(['user_id' => $data['user_id']]);
                    }),
                Tables\Actions\Action::make('dismiss')
                    ->label('Dismiss')
                    ->icon('heroicon-o-arrow-left-start-on-rectangle')
                    ->color('danger')
                    ->authorize(function ($record) {
                        if ($record->employee->isEmpty()) {
                            return false;
                        }

                        return $record->employee->last()->resignation_date === null && auth_user()->hasAbility(Permission::PEOPLE_DELETE->value);
                    })
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\DatePicker::make('resignation_date')
                            ->label('Resignation Date'),
                    ])
                    ->action(function ($record, array $data) {
                        if ($record->user !== null) {
                            $record->user->delete();
                        }

                        if ($record->employee->isNotEmpty()) {
                            $record->employee->last()->update(['resignation_date' => ($data['resignation_date'] ?? now())]);
                        }
                    }),
                Tables\Actions\Action::make('rehire')
                    ->label('Rehire')
                    ->icon('heroicon-o-arrow-left-end-on-rectangle')
                    ->color('warning')
                    ->authorize(function ($record) {
                        if ($record->employee->isEmpty()) {
                            return false;
                        }

                        $max30days  = strtotime($record->employee->last()->resignation_date) > now()->subDays(30)->timestamp;
                        $resignated = $record->employee->last()->resignation_date !== null;

                        return ($max30days && $resignated && auth_user()->hasAbility(Permission::PEOPLE_DELETE->value));
                    })
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if ($record->user !== null) {
                            $record->user->restore();
                        }

                        if ($record->employee->isNotEmpty()) {
                            $record->employee->last()->update(['resignation_date' => null]);
                        }
                    }),
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
            'index'  => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePeople::route('/create'),
            'edit'   => Pages\EditPeople::route('/{record}/edit'),
        ];
    }
}
