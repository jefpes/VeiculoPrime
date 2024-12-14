<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\{Pages};
use App\Models\{Employee, Role, User};
use Filament\Forms\Components\{CheckboxList, Fieldset};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->label('Employee')
                    ->options(function (string $operation, $record) {
                        if ($operation === 'create') {
                            return Employee::query()->orderBy('name')->where('resignation_date', null)->whereDoesntHave('user')->pluck('name', 'id');
                        }

                        return Employee::query()->orderBy('name')->where('resignation_date', null)->whereDoesntHave('user')->orWhere('id', $record->employee_id)->pluck('name', 'id');
                    })
                    ->optionsLimit(5)
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord:true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->visibleOn('create')
                    ->confirmed()
                    ->maxLength(8),
                Forms\Components\TextInput::make('password_confirmation')
                    ->visibleOn('create')
                    ->password()
                    ->requiredWith('password')
                    ->dehydrated(false)
                    ->maxLength(8),
                Fieldset::make('Roles')->schema([
                    CheckboxList::make('roles')
                        ->relationship('roles', 'name')
                        ->options(
                            Role::query()
                                ->where('hierarchy', '>=', Auth::user()->roles->min('hierarchy')) //@phpstan-ignore-line
                                ->orderBy('id')
                                ->pluck('name', 'id')
                        )
                        ->gridDirection('row')
                        ->bulkToggleable(),
                ])->label(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth_user()->tenant_id === null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
