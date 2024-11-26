<?php

namespace App\Filament\Resources;

use App\Enums\{PaymentMethod, StatusPayments};
use App\Filament\Resources\SaleResource\RelationManagers\InstallmentsRelationManager;
use App\Filament\Resources\SaleResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Helpers\{Contracts, Tools};
use App\Models\{Sale, Vehicle};
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\{FileUpload, Group, Section, Select, TextInput, ToggleButtons};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\{Average, Count, Sum, Summarizer};
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\{Blade, DB};
use Illuminate\Support\HtmlString;
use PhpOffice\PhpWord\TemplateProcessor;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?int $navigationSort = 13;

    public static function getNavigationGroup(): ?string
    {
        return __('Financial');
    }

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
                        ->label('Vehicle')
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            self::updateInstallmentValues($set, $get);
                        })
                        ->live()
                        ->options(function ($record) {
                            $query = Vehicle::whereNull('sold_date'); //@phpstan-ignore-line

                            if ($record !== null) {
                                $query->orWhere('id', $record->vehicle_id);
                            }

                            return $query->get()->mapWithKeys(function (Vehicle $vehicle) {
                                $price = $vehicle->promotional_price ?? $vehicle->sale_price;
                                $price = number_format($price, 2, ',', '.');
                                $price = "R$ {$price}";

                                return [
                                    $vehicle->id => "{$vehicle->plate} - {$vehicle->model->name} ({$vehicle->year_one}/{$vehicle->year_two}) - ({$price})",
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
                                    $client->id => "{$client->name} - {$client->client_id}",
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
                    MoneyInput::make('discount')
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
                    MoneyInput::make('total')->readOnly(),
                ])->columns(['sm' => 1, 'md' => 2, 'lg' => 3]),
                Section::make(__('Installments'))
                    ->visible(fn (Forms\Get $get) => $get('payment_type') === 'on_time')
                    ->columns(['sm' => 2, 'md' => 4])
                    ->schema([
                        MoneyInput::make('down_payment')
                            ->live(debounce: 1000)
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                self::updateInstallmentValues($set, $get);
                            }),
                        MoneyInput::make('interest_rate')
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
                        MoneyInput::make('installment_value')->readOnly(),
                        DatePicker::make('first_installment')->required(fn (Forms\Get $get) => $get('payment_type') === 'on_time'),
                        MoneyInput::make('interest')->readOnly(),
                        MoneyInput::make('total_with_interest')->readOnly(),
                    ]),
            ]);
    }

    public static function updateInstallmentValues(Forms\Set $set, Forms\Get $get): void
    {
        // Obtém os valores de entrada com valores padrão (0) quando vazio
        $total        = $get('total') ?? 0;
        $downPayment  = $get('down_payment') !== "" ? $get('down_payment') : 0;
        $interestRate = $get('interest_rate') !== "" ? $get('interest_rate') : 0;

        // Garante que o número de parcelas seja no mínimo 1
        $numberInstallments = $get('number_installments') != "" ? max((int) $get('number_installments'), 1) : 1;

        // Obtém o preço do veículo
        $vehicle      = \App\Models\Vehicle::find($get('vehicle_id')); //@phpstan-ignore-line
        $vehiclePrice = $vehicle->promotional_price ?? $vehicle->sale_price ?? 0;

        // Aplica desconto, se houver
        $discount = $get('discount') !== "" ? $get('discount') : 0;
        $interest = $get('interest') !== "" ? $get('interest') : 0;

        // Calcula o total
        $total = $vehiclePrice - $discount;
        $set('total', $total);

        // Se o pagamento for parcelado, calcula o valor da parcela
        if ($get('payment_type') === 'on_time') {
            $installmentValue = ($total - $downPayment) / $numberInstallments;
            $set('installment_value', round($installmentValue, 2)); // Arredonda para 2 casas decimais
        }

        // Calcula o principal após o pagamento inicial (entrada)
        $principal = $total - $downPayment;

        // Verifica se os juros são 0%
        if ($interestRate == 0) {
            // Sem juros: cálculo simples
            $installmentValue  = round($principal / $numberInstallments, 2);  // Cálculo simples da parcela
            $totalWithInterest = $principal;  // Total sem juros
            $interest          = 0;  // Sem juros
        } else {
            // Com juros compostos: chama o método para calcular os juros compostos
            $result            = Tools::calculateCompoundInterest($principal, $interestRate, $numberInstallments);
            $installmentValue  = $result['installment'];
            $totalWithInterest = $result['total'];  // Total com juros
            $interest          = round($totalWithInterest - $principal, 2);  // Valor dos juros
        }

        // Atualiza os campos do formulário
        $set('installment_value', $installmentValue);
        $set('interest', $interest);
        $set('total_with_interest', ($totalWithInterest + $downPayment));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
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
                    ->color(function (string $state, $record): string|array {
                        // Se o status for 'PENDENTE', verificar se há parcelas em atraso
                        if ($state === 'PENDENTE') {
                            // Verificar se a venda possui parcelas e se alguma está atrasada
                            $hasLateInstallments = $record->paymentInstallments()
                                ->where('status', '!=', 'PAGO') // Status diferente de pago
                                ->where('due_date', '<', now()) // Data de vencimento no passado
                                ->exists(); // Verificar se existe pelo menos uma

                            // Se houver parcelas em atraso, retornar 'danger'
                            if ($hasLateInstallments) {
                                return Color::hex('#b600ff');
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
                            ->when($data['sale_date_initial'], fn ($query, $value) => $query->where('date_sale', '>=', $value))
                            ->when($data['sale_date_final'], fn ($query, $value) => $query->where('date_sale', '<=', $value));
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
                        ->options(function () {
                            return \App\Models\User::all()->mapWithKeys(function ($user) {
                                return [
                                    $user->id => $user->name,
                                ];
                            });
                        }),
                ])->query(function ($query, array $data) {
                    return $query->when($data['seller'], fn ($query, $value) => $query->where('user_id', $value));
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['seller'] ?? null) {
                        $indicators[] = __('Seller') . ': ' . \App\Models\User::find($data['seller'])->name; //@phpstan-ignore-line
                    }

                    return $indicators;
                }),
                Filter::make('client')->form([
                    Select::make('client')
                        ->searchable()
                        ->options(function () {
                            return \App\Models\Client::all()->mapWithKeys(function ($client) {
                                return [
                                    $client->id => $client->name,
                                ];
                            });
                        }),
                ])->query(function ($query, array $data) {
                    return $query
                        ->when($data['client'], fn ($query, $value) => $query->where('client_id', $value));
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['client'] ?? null) {
                        $indicators[] = __('Client') . ': ' . \App\Models\Client::find($data['client'])->name; //@phpstan-ignore-line
                    }

                    return $indicators;
                }),
                Filter::make('payment_method')
                    ->form([
                        Forms\Components\Select::make('payment_method')
                        ->options(
                            collect(PaymentMethod::cases())
                                ->mapWithKeys(fn (PaymentMethod $type) => [$type->value => ucfirst($type->value)])
                        ),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when($data['payment_method'], fn ($query, $value) => $query->where('payment_method', $value));
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
                        ->options(
                            collect(StatusPayments::cases())
                                ->mapWithKeys(fn (StatusPayments $type) => [$type->value => ucfirst($type->value)])
                        ),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when($data['status'], fn ($query, $value) => $query->where('status', $value));
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
                            ->options(function () {
                                return \App\Models\VehicleModel::all()->mapWithKeys(function ($model) {
                                    return [
                                        $model->id => "{$model->name}",
                                    ];
                                });
                            }),
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
                            $modelName = \App\Models\VehicleModel::find($data['model'])->name ?? null; //@phpstan-ignore-line

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
