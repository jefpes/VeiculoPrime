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
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_one')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_two')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('father')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marital_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('spouse')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hiring_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resignation_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit'   => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
