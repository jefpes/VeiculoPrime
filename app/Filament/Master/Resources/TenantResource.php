<?php

namespace App\Filament\Master\Resources;

use App\Filament\Master\Resources\TenantResource\{Pages};
use App\Models\{Tenant};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Str;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('domain')
                    ->unique()
                    ->required()
                    ->maxLength(255)
                    ->live(debounce: 700)
                    ->afterStateUpdated(fn ($set, $get) => $set('domain', Str::slug($get('domain')))),
                Forms\Components\Section::make('User')
                    ->visible(fn (string $operation): bool => $operation === 'create')
                    ->schema([
                        Forms\Components\TextInput::make('user_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user_email')
                            ->unique(table: 'users', column: 'email')
                            ->email()
                            ->live()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user_password')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->confirmed()
                            ->maxLength(8),
                        Forms\Components\TextInput::make('user_password_confirmation')
                            ->password()
                            ->requiredWith('password')
                            ->dehydrated(false)
                            ->maxLength(8),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('include_in_marketplace'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit'   => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
