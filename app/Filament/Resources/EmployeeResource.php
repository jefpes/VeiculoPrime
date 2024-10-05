<?php

namespace App\Filament\Resources;

use App\Enums\{Genders, MaritalStatus, States};
use App\Filament\Resources\EmployeeResource\RelationManagers\PhotosRelationManager;
use App\Filament\Resources\EmployeeResource\{Pages};
use App\Forms\Components\{MoneyInput, PhoneInput};
use App\Models\Employee;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

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
                Section::make('Dados Pessoais')->schema([
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
                    Forms\Components\DatePicker::make('hiring_date')->required(),
                    Forms\Components\DatePicker::make('resignation_date'),
                ])->columns(['sm' => 1, 'md' => 2, 'lg' => 3]),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phones')
                    ->getStateUsing(function ($record) {
                        if ($record->phone_two !== null) {
                            return  $record->phone_one . ' | ' . $record->phone_two;
                        }

                        return  $record->phone_one;
                    })
                    ->label('Phone'),
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
                    ->date()
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
                Tables\Columns\TextColumn::make('hiring_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('resignation_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index'  => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit'   => Pages\EditEmployee::route('/{record}/edit'),
            'view'   => Pages\ViewEmployee::route('/{record}'),
        ];
    }
}
