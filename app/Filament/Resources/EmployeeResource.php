<?php

namespace App\Filament\Resources;

use App\Enums\{Genders, MaritalStatus};
use App\Filament\Resources\EmployeeResource\RelationManagers\PhotosRelationManager;
use App\Filament\Resources\EmployeeResource\{Pages};
use App\Forms\Components\{MoneyInput, PhoneInput};
use App\Helpers\AddressForm;
use App\Models\Employee;
use Filament\Forms\Components\{Grid};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return __('People');
    }

    public static function getModelLabel(): string
    {
        return __('Employee');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Employees');
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
                            PhoneInput::make('phone_one')->required(),
                            PhoneInput::make('phone_two'),
                            MoneyInput::make('salary')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('rg')
                                ->label('RG')
                                ->mask('99999999999999999999')
                                ->maxLength(20),
                            Forms\Components\TextInput::make('cpf')
                                ->label('CPF')
                                ->mask('999.999.999-99')
                                ->required()
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
                            Forms\Components\DatePicker::make('resignation_date'),
                        ]),
                    ]),
                    Forms\Components\Tabs\Tab::make('Endereço')->schema([
                        AddressForm::setAddressFields(),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('phoness')
                    ->getStateUsing(function ($record) {
                        if ($record->phone_two !== null) {
                            return  $record->phone_one . ' | ' . $record->phone_two;
                        }

                        return  $record->phone_one;
                    })
                    ->wrap(false)
                    ->label('Phone')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('salary')
                    ->numeric()
                    ->sortable()
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\Action::make('dismiss')
                    ->label('Dismiss')
                    ->icon('heroicon-o-arrow-left-start-on-rectangle')
                    ->color('danger')
                    ->authorize('delete')
                    ->authorize(function (Employee $employee) {
                        return $employee->resignation_date === null;
                    })
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\DatePicker::make('resignation_date')
                            ->label('Resignation Date'),
                    ])
                    ->action(function (Employee $employee, array $data) {
                        if ($employee->user() !== null) {
                            $employee->user()->delete();
                        }
                        $employee->update(['resignation_date' => ($data['resignation_date'] ?? now())]);
                    }),
                Tables\Actions\Action::make('rehire')
                    ->label('Rehire')
                    ->icon('heroicon-o-arrow-left-end-on-rectangle')
                    ->color('warning')
                    ->authorize('delete')
                    ->authorize(function (Employee $employee) {
                        return $employee->resignation_date !== null;
                    })
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\DatePicker::make('admission_date')
                            ->label('Admission Date'),
                    ])
                    ->action(function (Employee $employee, array $data) {
                        if ($employee->user() !== null) {
                            $employee->user()->restore(); //@phpstan-ignore-line
                        }

                        if ($data['admission_date'] === null) {
                            $employee->update(['resignation_date' => null]);

                            return;
                        }
                        $employee->update(['resignation_date' => null, 'admission_date' => ($data['admission_date'] ?? now())]);//@phpstan-ignore-line
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
            'index'  => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit'   => Pages\EditEmployee::route('/{record}/edit'),
            'view'   => Pages\ViewEmployee::route('/{record}'),
        ];
    }
}
