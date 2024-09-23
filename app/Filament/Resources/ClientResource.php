<?php

namespace App\Filament\Resources;

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
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('gender')
                        ->required()
                        ->options(collect(Genders::cases())->mapWithKeys(fn (Genders $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    Forms\Components\TextInput::make('rg')
                            ->label('RG')
                        ->required()
                        ->mask('99999999999999999999')
                        ->maxLength(20),
                    Forms\Components\TextInput::make('cpf')
                            ->label('CPF')
                        ->required()
                        ->mask('999.999.999-99')
                        ->maxLength(20),
                    Forms\Components\Select::make('marital_status')
                            ->label('Marital Status')
                        ->required()
                        ->options(collect(MaritalStatus::cases())->mapWithKeys(fn (MaritalStatus $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    PhoneInput::make('phone_one')
                        ->label('Phone (1)')
                        ->required(),
                    PhoneInput::make('phone_two')
                        ->label('Phone (2)'),
                    Forms\Components\DatePicker::make('birth_date')
                            ->label('Birth Date')
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])->columns(['sm' => 1, 'md' => 2, 'lg' => 3, 'xl' => 3]),

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
                        ->required()
                        ->maxLength(255),
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
                        default     => 'warning',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phones')
                    ->getStateUsing(function ($record) {
                        if ($record->phone_two !== null) {
                            return  $record->phone_one . ' | ' . $record->phone_two;
                        }

                        return  $record->phone_one;
                    })
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Birth Date')
                    ->date('d/m/Y')
                    ->sortable(),
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
            'index'  => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit'   => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
