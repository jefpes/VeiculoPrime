<?php

namespace App\Filament\Resources;

use App\Enums\{Genders, MaritalStatus, States};
use App\Filament\Resources\ClientResource\RelationManagers\{PhotosRelationManager};
use App\Filament\Resources\ClientResource\{Pages};
use App\Models\Client;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

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
                        ->required()
                        ->mask('99999999999999999999')
                        ->maxLength(20),
                    Forms\Components\TextInput::make('cpf')
                        ->required()
                        ->mask('999.999.999-99')
                        ->maxLength(20),
                    Forms\Components\Select::make('marital_status')
                        ->required()
                        ->options(collect(MaritalStatus::cases())->mapWithKeys(fn (MaritalStatus $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    Forms\Components\TextInput::make('phone_one')
                        ->tel()
                        ->required()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('phone_two')
                        ->tel()
                        ->maxLength(20),
                    Forms\Components\DatePicker::make('birth_date')
                        ->required(),
                    Forms\Components\TextInput::make('father')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('father_phone')
                        ->tel()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('mother')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('mother_phone')
                        ->tel()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('affiliated_one')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('affiliated_one_phone')
                        ->tel()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('affiliated_two')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('affiliated_two_phone')
                        ->tel()
                        ->maxLength(255),
                    Forms\Components\RichEditor::make('description')
                        ->maxLength(255),
                ]),
                Section::make('Address')->relationship('address')->schema([
                    Forms\Components\TextInput::make('zip_code')
                        ->required()
                        ->maxLength(255),
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
                ]),
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
                Tables\Columns\TextColumn::make('rg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marital_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_one')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_two')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('father')
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('affiliated_one')
                    ->searchable(),
                Tables\Columns\TextColumn::make('affiliated_one_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('affiliated_two')
                    ->searchable(),
                Tables\Columns\TextColumn::make('affiliated_two_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
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
            'index'  => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit'   => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
