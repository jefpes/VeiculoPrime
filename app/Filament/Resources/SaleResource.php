<?php

namespace App\Filament\Resources;

use App\Enums\PaymentMethod;
use App\Filament\Resources\SaleResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Models\{Sale, Vehicle};
use Filament\Forms\Components\{Section, ToggleButtons};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    // public static function getNavigationGroup(): ?string
    // {
    //     return __('Financial');
    // }

    public static function getModelLabel(): string
    {
        return __('Sale');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Sales');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('Seller')
                        ->relationship('user', 'name')
                        ->required(),
                    Forms\Components\Select::make('vehicle_id')
                        ->relationship('vehicle', 'id')
                        ->unique(ignoreRecord: true)
                        ->options(function () {
                            return Vehicle::where('sold_date', null)->get()->mapWithKeys(function (Vehicle $vehicle) { //@phpstan-ignore-line
                                $price = $vehicle->promotional_price ?? $vehicle->sale_price; //@phpstan-ignore-line
                                $price = number_format($price, 2, ',', '.');
                                $price = "R$ {$price}";

                                return [
                                    $vehicle->id => "{$vehicle->plate} - {$vehicle->model->name} ({$vehicle->year_one}/{$vehicle->year_two}) - ({$price})", //@phpstan-ignore-line
                                ];
                            });
                        })
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            $vehicle      = \App\Models\Vehicle::find($get('vehicle_id')); //@phpstan-ignore-line
                            $vehiclePrice = $vehicle->promotional_price ?? $vehicle->sale_price ?? 0;
                            $discount     = $get('discount') !== "" ? $get('discount') : 0;
                            $surcharge    = $get('surcharge') !== "" ? $get('surcharge') : 0;
                            $total        = $vehiclePrice + $surcharge - $discount;
                            $set('total', $total);
                        })
                        ->live()
                        ->required(),
                    Forms\Components\Select::make('client_id')
                        ->relationship('client', 'name')
                        ->searchable()
                        ->options(function () {
                            return \App\Models\Client::all()->mapWithKeys(function ($client) {
                                return [
                                    $client->id => "{$client->name} - {$client->client_id}", //@phpstan-ignore-line
                                ];
                            });
                        })
                        ->required(),
                    Forms\Components\Select::make('payment_method')
                        ->options(
                            collect(PaymentMethod::cases())
                                ->mapWithKeys(fn (PaymentMethod $type) => [$type->value => ucfirst($type->value)])
                        )
                        ->required(),
                    Forms\Components\DatePicker::make('date_sale')
                        ->required(),
                    ToggleButtons::make('discount_surcharge')
                        ->options([
                            'surcharge' => 'Acréscimo',
                            'discount'  => 'Desconto',
                        ])
                        ->label('Type')
                        ->live()
                        ->inline()
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            $set('surcharge', 0); // Define surcharge como null
                            $set('discount', 0); // Define discount como null
                            $vehicle      = \App\Models\Vehicle::find($get('vehicle_id')); //@phpstan-ignore-line
                            $vehiclePrice = $vehicle->promotional_price ?? $vehicle->sale_price ?? 0;
                            $discount     = $get('discount') !== "" ? $get('discount') : 0;
                            $surcharge    = $get('surcharge') !== "" ? $get('surcharge') : 0;
                            $total        = $vehiclePrice + $surcharge - $discount;
                            $set('total', $total);
                        }),
                    MoneyInput::make('discount')
                        ->label('Desconto')
                        ->live(debounce: 1000)
                        ->hidden(fn ($get) => $get('discount_surcharge') === 'surcharge')
                        ->required(fn (Forms\Get $get) => $get('discount_surcharge') === 'discount')
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            $vehicle      = \App\Models\Vehicle::find($get('vehicle_id')); //@phpstan-ignore-line
                            $vehiclePrice = $vehicle->promotional_price ?? $vehicle->sale_price ?? 0;
                            $discount     = $get('discount') !== "" ? $get('discount') : 0;
                            $surcharge    = $get('surcharge') !== "" ? $get('surcharge') : 0;
                            $total        = $vehiclePrice + $surcharge - $discount;
                            $set('total', $total);
                        }),
                    MoneyInput::make('surcharge')
                        ->label('Acréscimo')
                        ->live(debounce: 1000)
                        ->hidden(fn ($get) => $get('discount_surcharge') === 'discount')
                        ->required(fn (Forms\Get $get) => $get('discount_surcharge') === 'surcharge')
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            $vehicle      = \App\Models\Vehicle::find($get('vehicle_id')); //@phpstan-ignore-line
                            $vehiclePrice = $vehicle->promotional_price ?? $vehicle->sale_price ?? 0;
                            $discount     = $get('discount') !== "" ? $get('discount') : 0;
                            $surcharge    = $get('surcharge') !== "" ? $get('surcharge') : 0;
                            $total        = $vehiclePrice + $surcharge - $discount;
                            $set('total', $total);
                        }),
                    MoneyInput::make('total')
                        ->readOnly(),
                    ToggleButtons::make('payment_type')
                        ->options([
                            'in_sight' => 'À vista',
                            'on_time'  => 'A prazo',
                        ])
                        ->label('Type')
                        ->live()
                        ->inline()
                        ->afterStateUpdated(function (Forms\Set $set) {
                            $set('number_installments', 1);
                            $set('first_installment_date', now()->addMonth(1)->format('Y-m-d')); //@phpstan-ignore-line
                        }),
                ])->columns(['sm' => 1, 'md' => 2, 'lg' => 3]),
                Section::make(__('Installments'))->visible(
                    fn (Forms\Get $get) => $get('payment_type') === 'on_time'
                )->schema([
                    Forms\Components\TextInput::make('number_installments')
                        ->required(fn (Forms\Get $get) => $get('payment_type') === 'on_time')
                        ->numeric()
                        ->live(debounce: 1000)
                        ->default(1)
                        ->minValue(1)
                        ->afterStateUpdated(
                            function (Forms\Set $set, Forms\Get $get) {
                                $total            = $get('total');
                                $downPayment      = $get('down_payment') !== "" ? $get('down_payment') : 0;
                                $installmentValue = ($total - $downPayment) / ($get('number_installments') === '' ? 1 : $get('number_installments'));
                                $set('installment_value', $installmentValue);
                            }
                        ),
                    MoneyInput::make('down_payment')
                        ->required(fn (Forms\Get $get) => $get('payment_type') === 'on_time')
                        ->live(debounce: 1000)
                        ->afterStateUpdated(
                            function (Forms\Set $set, Forms\Get $get) {
                                $total            = $get('total');
                                $downPayment      = $get('down_payment') !== "" ? $get('down_payment') : 0;
                                $installmentValue = ($total - $downPayment) / ($get('number_installments') === '' ? 1 : $get('number_installments'));
                                $set('installment_value', $installmentValue);
                            }
                        ),
                    MoneyInput::make('installment_value')->readOnly(),
                    Forms\Components\DatePicker::make('first_installment')
                        ->required(fn (Forms\Get $get) => $get('payment_type') === 'on_time'),
                ])->columns(['sm' => 2, 'md' => 4]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Seller')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vehicle.model.name')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('date_sale')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_payment')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('surcharge')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('down_payment')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('number_installments')
                    ->label('Installments')
                    ->numeric(),
                Tables\Columns\TextColumn::make('reimbursement')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_cancel')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total')
                    ->money('BRL')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit'   => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
