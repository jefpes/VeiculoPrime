<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\{Pages};
use App\Models\{Role, User};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Auth;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('hierarchy')
                    ->options(
                        function () {
                            $user = Auth::user();

                            // Carregar as roles do usuário logado com a relação já carregada
                            $roles = $user->roles->pluck('hierarchy'); //@phpstan-ignore-line

                            // Verificar se o usuário tem roles
                            if ($roles->isNotEmpty()) {
                                // Pega o menor valor de hierarquia das roles
                                $minHierarchy = $roles->min();

                                // Gera o intervalo do menor nível de hierarquia até 100
                                return collect(range($minHierarchy, 100))->mapWithKeys(fn ($value) => [$value => $value]);
                            }

                            // Se o usuário não tiver roles, retorne um array vazio
                            return [];
                        }
                    )
                    ->searchable()
                    ->optionsLimit(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hierarchy')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn ($record) => $record->hierarchy >= User::with('roles')->find(Auth::user()->id)->roles->min('hierarchy')), //@phpstan-ignore-line
                Tables\Actions\DeleteAction::make()->visible(fn ($record) => $record->hierarchy >= User::with('roles')->find(Auth::user()->id)->roles->min('hierarchy')), //@phpstan-ignore-line
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}
