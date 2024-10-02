<?php

namespace App\Filament\Resources;

use App\Enums\PaymentMethod;
use App\Filament\Resources\SaleResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Models\{Sale, Vehicle};
use Carbon\Carbon;
use Filament\Forms\Components\{Section, ToggleButtons};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

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
                        ->options(function (Forms\Get $get) {
                            $selectedVehicleId = $get('vehicle_id');

                            $query = Vehicle::whereNull('sold_date'); //@phpstan-ignore-line

                            // Inclui o veículo selecionado, mesmo que esteja vendido
                            if ($selectedVehicleId) {
                                $query->orWhere('id', $selectedVehicleId);
                            }

                            return $query->get()->mapWithKeys(function (Vehicle $vehicle) {
                                $price = $vehicle->promotional_price ?? $vehicle->sale_price; //@phpstan-ignore-line
                                $price = number_format($price, 2, ',', '.');
                                $price = "R$ {$price}";

                                return [
                                    $vehicle->id => "{$vehicle->plate} - {$vehicle->model->name} ({$vehicle->year_one}/{$vehicle->year_two}) - ({$price})", //@phpstan-ignore-line
                                ];
                            });
                        })
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
                        ->required()
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            $set('surcharge', 0); // Define surcharge como null
                            $set('discount', 0); // Define discount como null
                            $vehicle      = \App\Models\Vehicle::find($get('vehicle_id')); //@phpstan-ignore-line
                            $vehiclePrice = $vehicle->promotional_price ?? $vehicle->sale_price ?? 0;
                            $discount     = $get('discount') !== "" ? $get('discount') : 0;
                            $surcharge    = $get('surcharge') !== "" ? $get('surcharge') : 0;
                            $total        = $vehiclePrice + $surcharge - $discount;
                            $set('total', $total);

                            if ($get('payment_type') === 'on_time') {
                                $downPayment      = $get('down_payment') !== "" ? $get('down_payment') : 0;
                                $installmentValue = ($total - $downPayment) / ($get('number_installments') === '' ? 1 : $get('number_installments'));
                                $set('installment_value', $installmentValue);
                            }
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

                            if ($get('payment_type') === 'on_time') {
                                $downPayment      = $get('down_payment') !== "" ? $get('down_payment') : 0;
                                $installmentValue = ($total - $downPayment) / ($get('number_installments') === '' ? 1 : $get('number_installments'));
                                $set('installment_value', $installmentValue);
                            }
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

                            if ($get('payment_type') === 'on_time') {
                                $downPayment      = $get('down_payment') !== "" ? $get('down_payment') : 0;
                                $installmentValue = ($total - $downPayment) / ($get('number_installments') === '' ? 1 : $get('number_installments'));
                                $set('installment_value', $installmentValue);
                            }
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
                        ->required()
                        ->inline()
                        ->afterStateUpdated(function (Forms\Set $set) {
                            $set('number_installments', 1);
                            $set('first_installment_date', now()->addMonth(1)->format('Y-m-d')); //@phpstan-ignore-line
                        }),
                ])->columns(['sm' => 1, 'md' => 2, 'lg' => 3]),
                Section::make(__('Installments'))->visible(
                    fn (Forms\Get $get) => $get('payment_type') === 'on_time'
                )->schema([
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
                    ->disabledClick()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vehicle.model.name')
                    ->disabledClick()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->disabledClick()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->disabledClick()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('date_sale')
                    ->disabledClick()
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_payment')
                    ->disabledClick()
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount')
                    ->disabledClick()
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('surcharge')
                    ->disabledClick()
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('down_payment')
                    ->disabledClick()
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('number_installments')
                    ->disabledClick()
                    ->label('Installments')
                    ->numeric(),
                Tables\Columns\TextColumn::make('reimbursement')
                    ->disabledClick()
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_cancel')
                    ->disabledClick()
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total')
                    ->disabledClick()
                    ->money('BRL')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Sale $sale) => $sale->status === 'PENDENTE'), //@phpstan-ignore-line
                Action::make('sale_cancel')
                    ->requiresConfirmation()
                    ->modalHeading(__('Cancel sale'))
                    ->modalDescription(function (Sale $sale) {
                        return new HtmlString(
                            Blade::render(
                                '<p>{{ __("Are you sure you want to cancel this sale?") }}</p>
                                <p>Veículo: {{ $sale->vehicle->plate }} - {{ $sale->vehicle->model->name }} ({{ $sale->vehicle->year_one }}/{{ $sale->vehicle->year_two }})</p>
                                <p>Data da venda: {{ $dateSale }}</p>
                                <p>Valor total: R$ {{ number_format($sale->total, 2, ",", ".") }}</p>
                                <p>Cliente: {{ $sale->client->name }}</p>
                                @if ($sale->discount > 0)
                                    <p>Desconto: R$ {{ number_format($sale->discount, 2, ",", ".") }} </p>
                                @endif
                                @if ($sale->surcharge > 0)
                                    <p>Acrescimo: R$ {{ number_format($sale->surcharge, 2, ",", ".") }} </p>
                                @endif
                                @if ($sale->down_payment > 0)
                                    <p>Entrada: R$ {{ number_format($sale->down_payment, 2, ",", ".") }} </p>
                                @endif
                                @if ($sale->number_installments > 1)
                                    <p>N° Parcelas: {{ $sale->paymentInstallments->count() }}</p>
                                    <p>Valor: R$ {{ number_format($sale->paymentInstallments[0]->value, 2, ",", ".") }}</p>
                                    <p>Parcelas pagas: {{ $sale->paymentInstallments->where("status", "PAGO")->count() }}</p>
                                    <p>Valor das parcelas pago: R$ {{ number_format($sale->paymentInstallments->where("status", "PAGO")->sum("value"), 2, ",", ".") }} </p>
                                @endif
                                    <p>Total recebido: R$ {{ number_format(($sale->paymentInstallments->where("status", "PAGO")->sum("value") ?? 0)+($sale->down_payment ?? 0), 2, ",", ".") }} </p>',
                                [
                                    'sale'          => $sale,
                                    'valueReceived' => $sale->paymentInstallments->sum('value'), //@phpstan-ignore-line
                                    'datePayment'   => $sale->date_payment === null ? null : Carbon::parse($sale->date_payment)->format('d/m/Y'), //@phpstan-ignore-line
                                    'dateSale'      => $sale->date_sale === null ? null : Carbon::parse($sale->date_sale)->format('d/m/Y'), //@phpstan-ignore-line
                                ]
                            )
                        );

                    })
                    ->label('Cancel')
                    ->translateLabel()
                    ->icon('heroicon-o-x-circle')
                    ->iconSize('md')
                    ->color('danger')
                    ->form([
                        MoneyInput::make('reimbursement')
                            ->label('Reimbursement')
                            ->live(debounce: 500),
                    ])
                    ->action(function (Sale $sale, array $data) {
                        $sale->update([
                            'date_cancel'   => Carbon::now()->format('Y-m-d'),
                            'reimbursement' => $data['reimbursement'] !== "" ? $data['reimbursement'] : 0,
                            'status'        => $data['reimbursement'] !== null ? 'REEMBOLSADO' : 'CANCELADO',
                        ]);

                        Vehicle::find($sale->vehicle_id)->update(['sold_date' => null]); //@phpstan-ignore-line
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
            'index'  => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit'   => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
