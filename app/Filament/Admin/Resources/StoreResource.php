<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Clusters\ManagementCluster;
use App\Filament\Admin\Resources\StoreResource\{Pages};
use App\Forms\Components\{ZipCode};
use App\Models\Store;
use App\Tools\FormFields;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Tables};
use Illuminate\Support\Str;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $cluster = ManagementCluster::class;

    protected static ?int $navigationSort = 11;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return auth_user()->navigation_mode ? SubNavigationPosition::Start : SubNavigationPosition::Top;
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $isScopedToTenant = false;

    public static function getModelLabel(): string
    {
        return __('Store');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Stores');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true, debounce: 1000)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state, '-')))
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Subdomain')
                            ->required()
                            ->readOnly()
                            ->maxLength(255),
                        Forms\Components\Grid::make()
                        ->columns(2)
                        ->schema([
                            Forms\Components\Grid::make()
                            ->columnSpan(1)
                            ->schema([
                                ZipCode::make('zip_code'),
                                Forms\Components\TextInput::make('state')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('neighborhood')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Grid::make()
                                    ->columns(5)
                                    ->schema([
                                        Forms\Components\TextInput::make('street')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(['md' => 4, 'sm' => 5]),
                                        Forms\Components\TextInput::make('number')
                                            ->columnSpan(['md' => 1, 'sm' => 5])
                                            ->minValue(0),
                                    ]),
                                Forms\Components\Textarea::make('complement')
                                    ->maxLength(255)
                                    ->rows(1)
                                    ->columnSpanFull(),
                            ]),
                            Forms\Components\Grid::make()->columnSpan(1)->columns(1)->schema([
                                FormFields::setPhoneFields()->grid(1),
                            ]),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('neighborhood')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('street')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('complement')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phones.full_phone')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('transfer')
                    ->requiresConfirmation()
                    ->modalHeading(__('Transfer all vehicles, not sale, to another store'))
                    ->modalDescription(__('Are you sure you want this? All vehicles that are not sold will be transferred to another store, this not be undone'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('warning')
                    ->form([
                        Select::make('store')
                            ->required()
                            ->helperText(__('Select the store to which the vehicles will be transferred'))
                            ->options(function ($record) {
                                return Store::query()
                                    ->whereNot('id', $record->id)
                                    ->orderBy('name')
                                    ->pluck('name', 'id');
                            }),
                    ])
                    ->action(function (array $data, Store $store) {
                        $newStoreId = $data['store'];

                        $vehicles = $store->vehicles()->where('sold_date', null)->with('expenses')->get();

                        if ($vehicles->isNotEmpty()) {
                            foreach ($vehicles as $vehicle) {
                                $vehicle->update(['store_id' => $newStoreId]);

                                if ($vehicle->expenses->isNotEmpty()) { //@phpstan-ignore-line
                                    $vehicle->expenses()->update(['store_id' => $newStoreId]); //@phpstan-ignore-line
                                }
                            }
                        }

                        Notification::make()
                            ->body(__('Vehicles and their expenses transferred successfully'))
                            ->icon('heroicon-o-check-circle')
                            ->iconColor('success')
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('Transfer all vehicles, not sale, to another store'))
                    ->modalDescription(__('Are you sure you want this? All vehicles that are not sold will be transferred to another store, this not be undone'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->form([
                        Select::make('store')
                            ->required()
                            ->helperText(__('Select the store to which the vehicles will be transferred'))
                            ->options(function ($record) {
                                return Store::query()
                                    ->whereNot('id', $record->id)
                                    ->orderBy('name')
                                    ->pluck('name', 'id');
                            }),
                    ])
                    ->action(function (array $data, Store $store) {
                        $newStoreId = $data['store'];

                        $vehicles = $store->vehicles()->with('expenses')->get();

                        if ($vehicles->isNotEmpty()) {
                            foreach ($vehicles as $vehicle) {
                                if ($vehicle->expenses->isNotEmpty()) { //@phpstan-ignore-line
                                    $vehicle->expenses()->update(['store_id' => $newStoreId]); //@phpstan-ignore-line
                                }

                                if ($vehicle->sale()->exists()) { //@phpstan-ignore-line
                                    if ($vehicle->paymentInstallments()->exists()) { //@phpstan-ignore-line
                                        foreach ($vehicle->paymentInstallments as $installment) { //@phpstan-ignore-line
                                            $installment->update(['store_id' => $newStoreId]);
                                        }
                                    }

                                    $vehicle->sale()->update(['store_id' => $newStoreId]); //@phpstan-ignore-line

                                }
                                $vehicle->update(['store_id' => $newStoreId]);
                            }
                        }

                        if ($store->users()->exists()) {
                            $usersToTransfer = $store->users;

                            foreach ($usersToTransfer as $user) { //@phpstan-ignore-line
                                $user->stores()->detach($store->id);

                                if (!$user->stores()->where('store_id', $newStoreId)->exists()) {
                                    $user->stores()->attach($newStoreId);
                                }
                            }
                        }

                        $store->delete();

                        redirect()->route('filament.admin.auth.login');

                        Notification::make()
                            ->body(__('Vehicles and their expenses transferred successfully'))
                            ->icon('heroicon-o-check-circle')
                            ->iconColor('success')
                            ->send();
                    }),

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
            'index'  => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit'   => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
