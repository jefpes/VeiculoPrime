<?php

namespace App\Filament\Admin\Resources;

use App\Enums\{PaymentMethod, StatusPayments};
use App\Filament\Admin\Clusters\FinancialCluster;
use App\Filament\Admin\Resources\SaleResource\RelationManagers\InstallmentsRelationManager;
use App\Filament\Admin\Resources\SaleResource\{Pages};
use App\Models\{People, Sale, Store, Vehicle, VehicleModel};
use App\Tools\{Contracts};
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\{FileUpload, Group, Section, Select, TextInput, ToggleButtons};
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\{Average, Count, Sum, Summarizer};
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\{Blade, DB};
use Illuminate\Support\HtmlString;
use Leandrocfe\FilamentPtbrFormFields\Money;
use PhpOffice\PhpWord\TemplateProcessor;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $cluster = FinancialCluster::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return auth_user()->navigation_mode ? SubNavigationPosition::Start : SubNavigationPosition::Top;
    }

    protected static ?int $navigationSort = 31;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

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
                    Forms\Components\Select::make('seller_id')
                        ->label('Seller')
                        ->options(function ($record) {
                            return People::query()
                                ->orderBy('name')
                                ->where(function ($query) use ($record) {
                                    $query->whereHas(
                                        'employee',
                                        fn ($query) => $query->where('resignation_date', null)
                                    )
                                    ->when($record, fn ($q) => $q->orWhere('id', $record->seller_id));
                                })
                                ->get()
                                ->mapWithKeys(fn (People $person) => [ // @phpstan-ignore-line
                                    $person->id => sprintf(
                                        '%s - %s',
                                        $person->name,
                                        $person->person_id,
                                    ),
                                ]);
                        })
                        ->required(),
                    Forms\Components\Select::make('client_id')
                        ->label('Client')
                        ->options(function ($record) {
                            return People::query()
                                ->orderBy('name')
                                ->where(function ($query) use ($record) {
                                    $query->where('client', true)
                                        ->when($record, fn ($q) => $q->orWhere('id', $record->vehicle_id));
                                })
                                ->get()
                                ->mapWithKeys(fn (People $person) => [ // @phpstan-ignore-line
                                    $person->id => sprintf(
                                        '%s - %s',
                                        $person->name,
                                        $person->person_id,
                                    ),
                                ]);
                        })
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('vehicle_id')
                        ->label('Vehicle')
                        ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateInstallmentValues($set, $get))
                        ->live()
                        ->options(function ($record) {
                            return Vehicle::query()
                                ->where(function ($query) use ($record) {
                                    $query->whereNull('sold_date')
                                        ->when($record, fn ($q) => $q->orWhere('id', $record->vehicle_id));
                                })
                                ->get()
                                ->mapWithKeys(fn (Vehicle $vehicle) => [
                                    $vehicle->id => sprintf(
                                        '%s - %s (%s/%s) - (R$ %s)',
                                        $vehicle->plate,
                                        $vehicle->model->name,
                                        $vehicle->year_one,
                                        $vehicle->year_two,
                                        number_format($vehicle->promotional_price ?? $vehicle->sale_price, 2, ',', '.')
                                    ),
                                ]);
                        })
                        ->required(),
                    Forms\Components\Select::make('payment_method')
                            ->options(PaymentMethod::class),
                    Forms\Components\DatePicker::make('date_sale')
                        ->required(),
                    Money::make('discount')
                        ->label('Desconto')
                        ->live(debounce: 1000)
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            self::updateInstallmentValues($set, $get);
                        }),
                    ToggleButtons::make('payment_type')
                        ->options([
                            'in_sight' => 'À vista',
                            'on_time'  => 'A prazo',
                        ])
                        ->label('Type')
                        ->live()
                        ->inline()
                        ->required(),
                    Money::make('total')->readOnly(),
                ])->columns(['sm' => 1, 'md' => 2, 'lg' => 3]),
                Section::make(__('Installments'))
                    ->visible(fn (Forms\Get $get) => $get('payment_type') === 'on_time')
                    ->columns(['sm' => 2, 'md' => 4])
                    ->schema([
                        Money::make('down_payment')
                            ->live(debounce: 1000)
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                self::updateInstallmentValues($set, $get);
                            }),
                        Money::make('interest_rate')
                            ->prefix(null)
                            ->suffix('%')
                            ->live(debounce: 1000)
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                self::updateInstallmentValues($set, $get);
                            }),
                        TextInput::make('number_installments')
                            ->required(fn (Forms\Get $get) => $get('payment_type') === 'on_time')
                            ->numeric()
                            ->live(debounce: 1000)
                            ->minValue(0)
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                self::updateInstallmentValues($set, $get);
                            }),
                        Money::make('installment_value')->readOnly(),
                        DatePicker::make('first_installment')->required(fn (Forms\Get $get) => $get('payment_type') === 'on_time'),
                        Money::make('interest')->readOnly(),
                        Money::make('total_with_interest')->readOnly(),
                    ]),
            ]);
    }

    public static function updateInstallmentValues(Forms\Set $set, Forms\Get $get): void
    {
        // Obtém os valores de entrada com valores padrão (0) quando vazio
        $discount     = string_money_to_float($get('discount') ?? '0');
        $total        = string_money_to_float($get('total') ?? '0');
        $downPayment  = string_money_to_float($get('down_payment') ?? '0');
        $interestRate = string_money_to_float($get('interest_rate') ?? '0');

        // Garante que o número de parcelas seja no mínimo 1
        $numberInstallments = max((int) ($get('number_installments') ?? '1'), '1');

        // Obtém o preço do veículo
        $vehicle      = Vehicle::query()->find($get('vehicle_id'));
        $vehiclePrice = $vehicle ? $vehicle->promotional_price ?? $vehicle->sale_price ?? '0' : '0';

        // Calcula o total
        $total = bcsub($vehiclePrice, $discount, 2);
        $set('total', number_format((float)$total, 2, ',', '.'));

        // Se o pagamento for parcelado, calcula o valor da parcela
        if ($get('payment_type') === 'on_time') {
            $principal = bcsub($total, $downPayment, 2);

            // Verifica se os juros são 0%
            if ($interestRate == 0) {
                // Sem juros: cálculo simples
                $installmentValue  = bcdiv($principal, $numberInstallments, 2);
                $totalWithInterest = $principal;
                $interest          = '0';
            } else {
                // Com juros compostos
                $result            = calculate_compound_interest($principal, $interestRate, (string)$numberInstallments);
                $installmentValue  = $result['installment'];
                $totalWithInterest = $result['total'];
                $interest          = bcsub($totalWithInterest, $principal, 2);
            }

            // Atualiza os campos do formulário
            $set('installment_value', number_format($installmentValue, 2, ',', '.')); //@phpstan-ignore-line
            $set('interest', number_format($interest, 2, ',', '.')); //@phpstan-ignore-line
            $set('total_with_interest', number_format(bcadd($totalWithInterest, $downPayment, 2), 2, ',', '.')); //@phpstan-ignore-line
        } else {
            // Pagamento à vista
            $set('installment_value', '0,00');
            $set('interest', '0,00');
            $set('total_with_interest', number_format($total, 2, ',', '.')); //@phpstan-ignore-line
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vehicle')
                    ->getStateUsing(fn (Sale $record) => $record->vehicle->plate . ' - ' . $record->vehicle->model->name)
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('client.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function (string $state, $record): string {
                        // Se o status for 'PENDENTE', verificar se há parcelas em atraso
                        if ($state === 'PENDENTE') {
                            // Verificar se a venda possui parcelas e se alguma está atrasada
                            $hasLateInstallments = $record->paymentInstallments()
                                ->where('status', '!=', 'PAGO') // Status diferente de pago
                                ->where('due_date', '<', now()) // Data de vencimento no passado
                                ->exists(); // Verificar se existe pelo menos uma

                            // Se houver parcelas em atraso, retornar 'danger'
                            if ($hasLateInstallments) {
                                return 'pink';
                            }

                            // Caso não haja parcelas em atraso, manter a cor 'info'
                            return 'info';
                        }

                        // Verificar os demais estados
                        return match ($state) {
                            'REEMBOLSADO' => 'warning',
                            'CANCELADO'   => 'danger',
                            default       => 'success',
                        };
                    }),
                Tables\Columns\TextColumn::make('date_sale')
                    ->date('d/m/Y')
                    ->sortable()
                    ->summarize(Count::make())
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_payment')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('interest')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('interest_rate')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Average::make()->suffix('%')),
                Tables\Columns\TextColumn::make('down_payment')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('number_installments')
                    ->label('Installments')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->summarize(Average::make()),
                Tables\Columns\TextColumn::make('reimbursement')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('date_cancel')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Date Cancel')
                    ->summarize(Count::make()),
                Tables\Columns\TextColumn::make('total')
                    ->money('BRL')
                    ->sortable()
                    ->getStateUsing(fn (Sale $record) => $record->total_with_interest ?? $record->total)
                    ->summarize(
                        Summarizer::make()->using(function ($query) {
                            return $query->sum(DB::raw('COALESCE(total_with_interest, total)'));
                        })
                        ->money('BRL')
                        ->label('Total')
                    ),
            ])
            ->filtersTriggerAction(fn (Tables\Actions\Action $action) => $action->slideOver())
            ->filters([
                Filter::make('date_sale')
                    ->form([
                        Group::make([
                            DatePicker::make('sale_date_initial')->label('Sale Date After'),
                            DatePicker::make('sale_date_final')->label('Sale Date Before'),
                        ])->columns(2),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when($data['sale_date_initial'], fn ($query) => $query->where('date_sale', '>=', $data['sale_date_initial']))
                            ->when($data['sale_date_final'], fn ($query) => $query->where('date_sale', '<=', $data['sale_date_final']));
                    })->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['sale_date_initial'] ?? null) {
                            $indicators[] = __('Sale Date After') . ': ' . Carbon::parse($data['sale_date_initial'])->format('d/m/Y');
                        }

                        if ($data['sale_date_final'] ?? null) {
                            $indicators[] = __('Sale Date Before') . ': ' . Carbon::parse($data['sale_date_final'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
                Filter::make('seller')->form([
                    Select::make('seller')
                        ->searchable()
                        ->options(fn () => \App\Models\People::query()->orderBy('name')->whereHas('seller')->get()->pluck('name', 'id')),
                ])->query(function ($query, array $data) {
                    return $query->when($data['seller'], fn ($query) => $query->where('seller_id', $data['seller']));
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['seller'] ?? null) {
                        $indicators[] = __('Seller') . ': ' . \App\Models\People::find($data['seller'])->name; //@phpstan-ignore-line
                    }

                    return $indicators;
                }),
                Filter::make('client')->form([
                    Select::make('client')
                        ->searchable()
                        ->options(function () {
                            return \App\Models\People::query()->orderBy('name')->whereHas('client')->get()->pluck('name', 'id');
                        }),
                ])->query(function ($query, array $data) {
                    return $query
                        ->when($data['client'], fn ($query) => $query->where('client_id', $data['client']));
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['client'] ?? null) {
                        $indicators[] = __('Client') . ': ' . \App\Models\People::find($data['client'])->name; //@phpstan-ignore-line
                    }

                    return $indicators;
                }),
                Filter::make('payment_method')
                    ->form([
                        Forms\Components\Select::make('payment_method')
                            ->options(PaymentMethod::class),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when($data['payment_method'], fn ($query) => $query->where('payment_method', $data['payment_method']));
                    })->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['payment_method'] ?? null) {
                            $indicators[] = __('Payment Method') . ': ' . $data['payment_method'];
                        }

                        return $indicators;
                    }),
                Filter::make('status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options(StatusPayments::class),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when($data['status'], fn ($query) => $query->where('status', $data['status']));
                    })->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['status'] ?? null) {
                            $indicators[] = __('Status') . ': ' . $data['status'];
                        }

                        return $indicators;
                    }),
                Filter::make('model')
                    ->form([
                        Select::make('model')
                            ->searchable()
                            ->options(
                                VehicleModel::query()->orderBy('name')
                                    ->whereHas(
                                        'vehicles',
                                        fn ($query) => $query->whereHas('sale')
                                    )
                                    ->get()
                                    ->pluck('name', 'id')
                            ),
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['model'])) {
                            return $query->whereHas('vehicle', function ($query) use ($data) {
                                $query->where('vehicle_model_id', $data['model']);
                            });
                        }

                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (!empty($data['model'])) {
                            $modelName = VehicleModel::find($data['model'])->name ?? null; //@phpstan-ignore-line

                            if ($modelName) {
                                $indicators[] = __('Model') . ': ' . $modelName;
                            }
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('sale_cancel')
                    ->authorize('saleCancel')
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
                                @if ($sale->interest > 0)
                                    <p>Acrescimo: R$ {{ number_format($sale->interest, 2, ",", ".") }} </p>
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
                                    'datePayment'   => $sale->date_payment === null ? null : Carbon::parse($sale->date_payment)->format('d/m/Y'),
                                    'dateSale'      => $sale->date_sale === null ? null : Carbon::parse($sale->date_sale)->format('d/m/Y'),
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
                        Money::make('reimbursement')
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
                Tables\Actions\Action::make('transfer')
                    ->requiresConfirmation()
                    ->modalHeading(__('Transfer'))
                    ->modalDescription(__('Are you sure you want to transfer this sale? The vehicle, expenses and installments records will also be transferred'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('warning')
                    ->form([
                        Select::make('store')
                            ->required()
                            ->helperText(__('Select the store to which the sale will be transferred.'))
                            ->options(function ($record) {
                                return Store::query()
                                    ->whereNot('id', $record->store_id)
                                    ->orderBy('name')
                                    ->pluck('name', 'id');
                            }),
                    ])
                    ->action(function (array $data, Sale $sale) {
                        $newStore = $data['store'];

                        if ($sale->vehicle->expenses()->exists()) { //@phpstan-ignore-line
                            foreach ($sale->vehicle->expenses as $expenses) { //@phpstan-ignore-line
                                $expenses->update(['store_id' => $newStore]);
                            }
                        }

                        if ($sale->paymentInstallments()->exists()) { //@phpstan-ignore-line
                            foreach ($sale->paymentInstallments as $installment) { //@phpstan-ignore-line
                                $installment->update(['store_id' => $newStore]);
                            }
                        }

                        $sale->vehicle->update(['store_id' => $newStore]);
                        $sale->store_id = $newStore;
                        $sale->save();

                        Notification::make()->body(__('Sale transferred successfully'))->icon('heroicon-o-check-circle')->iconColor('success')->send();
                    }),
                Action::make('contract')
                    ->requiresConfirmation()
                    ->modalHeading(__('Contract'))
                    ->label('Contract')
                    ->translateLabel()
                    ->icon('heroicon-o-document')
                    ->iconSize('md')
                    ->color('info')
                    ->form([
                        FileUpload::make('contract')
                            ->label('Contract')
                            ->panelAspectRatio('2:1')
                            ->storeFiles(false)
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ]),
                    ])
                    ->action(function (array $data, Sale $sale) {

                        $template = new TemplateProcessor($data['contract']->getRealPath());

                        $caminho = Contracts::generateSaleContract($template, $sale);

                        return response()->download($caminho)->deleteFileAfterSend(true);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InstallmentsRelationManager::class,
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
