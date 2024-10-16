<?php

namespace App\Filament\Resources;

use App\Enums\{PaymentMethod, StatusPayments};
use App\Filament\Resources\SaleResource\RelationManagers\InstallmentsRelationManager;
use App\Filament\Resources\SaleResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Models\{Sale, Vehicle};
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\{FileUpload, Section, Select, ToggleButtons};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use PhpOffice\PhpWord\TemplateProcessor;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?int $navigationSort = 0;

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
                        ->relationship('vehicle', 'id')
                        ->options(function ($record) {
                            $query = Vehicle::whereNull('sold_date'); //@phpstan-ignore-line

                            if ($record !== null) {
                                $query->orWhere('id', $record->vehicle_id);
                            }

                            return $query->get()->mapWithKeys(function (Vehicle $vehicle) {
                                $price = $vehicle->promotional_price ?? $vehicle->sale_price;  //@phpstan-ignore-line
                                $price = number_format($price, 2, ',', '.');
                                $price = "R$ {$price}";

                                return [
                                    $vehicle->id => "{$vehicle->plate} - {$vehicle->model->name} ({$vehicle->year_one}/{$vehicle->year_two}) - ({$price})",  //@phpstan-ignore-line
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
            ->recordUrl(null)
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
                                return 'danger';
                            }

                            // Caso não haja parcelas em atraso, manter a cor 'info'
                            return 'info';
                        }

                        // Verificar os demais estados
                        return match ($state) {
                            'REEMBOLSADO' => 'warning',
                            'CANCELADO'   => Color::hex('#fe0000'),
                            default       => 'success',
                        };
                    }),
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
                Filter::make('date_sale')
                    ->form([
                        DatePicker::make('sale_date_initial')->label('Sale Date After'),
                        DatePicker::make('sale_date_final')->label('Sale Date Before'),
                    ])->query(function (Builder $query, array $data): Builder {
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
                Filter::make('payment_method')
                    ->form([
                        Forms\Components\Select::make('payment_method')
                        ->options(
                            collect(PaymentMethod::cases())
                                ->mapWithKeys(fn (PaymentMethod $type) => [$type->value => ucfirst($type->value)])
                        ),
                    ])->query(function (Builder $query, array $data): Builder {
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
                    ])->query(function (Builder $query, array $data): Builder {
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
                                        $model->id => "{$model->name}", //@phpstan-ignore-line
                                    ];
                                });
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
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
            ], layout: FiltersLayout::Modal)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn (Sale $sale) => $sale->status === 'REEMBOLSADO' || $sale->status === 'CANCELADO'), //@phpstan-ignore-line
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

                        // Substitui os placeholders com os dados do cliente
                        $template->setValue('cliente_nome', $sale->client->name); //@phpstan-ignore-line
                        $template->setValue('cliente_genero', $sale->client->gender); //@phpstan-ignore-line
                        $template->setValue('cliente_tipo', $sale->client->client_type); //@phpstan-ignore-line
                        $template->setValue('cliente_rg', $sale->client->rg); //@phpstan-ignore-line
                        $template->setValue('cliente_cpf/cnpj', $sale->client->client_id); //@phpstan-ignore-line
                        $template->setValue('cliente_estdo_civil', $sale->client->marital_status); //@phpstan-ignore-line
                        $template->setValue('cliente_telefone_1', $sale->client->phone_one); //@phpstan-ignore-line
                        $template->setValue('cliente_telefone_2', $sale->client->phone_two); //@phpstan-ignore-line
                        $template->setValue('cliente_data_de_nascimento', Carbon::parse($sale->client->birth_date)->format('d/m/Y')); //@phpstan-ignore-line
                        $template->setValue('cliente_pai', $sale->client->father); //@phpstan-ignore-line
                        $template->setValue('cliente_telefone_pai', $sale->client->father_phone); //@phpstan-ignore-line
                        $template->setValue('cliente_mae', $sale->client->mother); //@phpstan-ignore-line
                        $template->setValue('cliente_telefone_mae', $sale->client->mother_phone); //@phpstan-ignore-line
                        $template->setValue('cliente_afiliado_1', $sale->client->affiliated_one); //@phpstan-ignore-line
                        $template->setValue('cliente_telefone_afiliado_1', $sale->client->affiliated_one_phone); //@phpstan-ignore-line
                        $template->setValue('cliente_afiliado_2', $sale->client->affiliated_two); //@phpstan-ignore-line
                        $template->setValue('cliente_telefone_afiliado_2', $sale->client->affiliated_two_phone); //@phpstan-ignore-line
                        $template->setValue('cliente_descricao', $sale->client->description); //@phpstan-ignore-line

                        //Substitui os placeholders com os dados do endereco do cliente
                        $template->setValue('cliente_cep', $sale->client->address->cep); //@phpstan-ignore-line
                        $template->setValue('cliente_rua', $sale->client->address->street); //@phpstan-ignore-line
                        $template->setValue('cliente_numero', $sale->client->address->number); //@phpstan-ignore-line
                        $template->setValue('cliente_bairro', $sale->client->address->neighborhood); //@phpstan-ignore-line
                        $template->setValue('cliente_cidade', $sale->client->address->city->name); //@phpstan-ignore-line
                        $template->setValue('cliente_estado', $sale->client->address->state); //@phpstan-ignore-line
                        $template->setValue('cliente_complemento', $sale->client->address->complement); //@phpstan-ignore-line

                        //Substitui os placeholders com os dados do veiculo
                        $template->setValue('data_compra', Carbon::parse($sale->vehicle->purchase_date)->format('d/m/Y')); //@phpstan-ignore-line
                        $template->setValue('preco_fipe', number_format($sale->vehicle->fipe_price, 2, ',', '.')); //@phpstan-ignore-line
                        $template->setValue('preco_compra', number_format($sale->vehicle->purchase_price, 2, ',', '.')); //@phpstan-ignore-line
                        $template->setValue('preco_venda', number_format($sale->vehicle->promotional_price ?? $sale->vehicle->sale_price, 2, ',', '.')); //@phpstan-ignore-line
                        $template->setValue('modelo', $sale->vehicle->model->name); //@phpstan-ignore-line
                        $template->setValue('tipo', $sale->vehicle->model->type->name); //@phpstan-ignore-line
                        $template->setValue('marca', $sale->vehicle->model->brand->name); //@phpstan-ignore-line
                        $template->setValue('ano_um', $sale->vehicle->year_one); //@phpstan-ignore-line
                        $template->setValue('ano_dois', $sale->vehicle->year_two); //@phpstan-ignore-line
                        $template->setValue('km', $sale->vehicle->km); //@phpstan-ignore-line
                        $template->setValue('combustivel', $sale->vehicle->fuel); //@phpstan-ignore-line
                        $template->setValue('motor_potencia', $sale->vehicle->engine_power); //@phpstan-ignore-line
                        $template->setValue('direcao', $sale->vehicle->steering); //@phpstan-ignore-line
                        $template->setValue('transmissao', $sale->vehicle->transmission); //@phpstan-ignore-line
                        $template->setValue('portas', $sale->vehicle->doors); //@phpstan-ignore-line
                        $template->setValue('seats', $sale->vehicle->seats); //@phpstan-ignore-line
                        $template->setValue('tracao', $sale->vehicle->traction); //@phpstan-ignore-line
                        $template->setValue('cor', $sale->vehicle->color); //@phpstan-ignore-line
                        $template->setValue('placa', $sale->vehicle->plate); //@phpstan-ignore-line
                        $template->setValue('chassi', $sale->vehicle->chassi); //@phpstan-ignore-line
                        $template->setValue('renavam', $sale->vehicle->renavam); //@phpstan-ignore-line
                        $template->setValue('descricao', $sale->vehicle->description); //@phpstan-ignore-line
                        $template->setValue('anotacao', $sale->vehicle->annotation); //@phpstan-ignore-line

                        //Substitui os placeholders com os dados do fornecedor
                        $template->setValue('fornecedor_nome', $sale->supplier->name); //@phpstan-ignore-line
                        $template->setValue('fornecedor_genero', $sale->supplier->gender); //@phpstan-ignore-line
                        $template->setValue('fornecedor_tipo', $sale->supplier->client_type); //@phpstan-ignore-line
                        $template->setValue('fornecedor_rg', $sale->supplier->rg); //@phpstan-ignore-line
                        $template->setValue('fornecedor_cpf/cnpj', $sale->supplier->client_id); //@phpstan-ignore-line
                        $template->setValue('fornecedor_estdo_civil', $sale->supplier->marital_status); //@phpstan-ignore-line
                        $template->setValue('fornecedor_telefone_1', $sale->supplier->phone_one); //@phpstan-ignore-line
                        $template->setValue('fornecedor_telefone_2', $sale->supplier->phone_two); //@phpstan-ignore-line
                        $template->setValue('fornecedor_data_de_nascimento', Carbon::parse($sale->supplier->birth_date)->format('d/m/Y')); //@phpstan-ignore-line
                        $template->setValue('fornecedor_pai', $sale->supplier->father); //@phpstan-ignore-line
                        $template->setValue('fornecedor_telefone_pai', $sale->supplier->father_phone); //@phpstan-ignore-line
                        $template->setValue('fornecedor_mae', $sale->supplier->mother); //@phpstan-ignore-line
                        $template->setValue('fornecedor_telefone_mae', $sale->supplier->mother_phone); //@phpstan-ignore-line
                        $template->setValue('fornecedor_afiliado_1', $sale->supplier->affiliated_one); //@phpstan-ignore-line
                        $template->setValue('fornecedor_telefone_afiliado_1', $sale->supplier->affiliated_one_phone); //@phpstan-ignore-line
                        $template->setValue('fornecedor_afiliado_2', $sale->supplier->affiliated_two); //@phpstan-ignore-line
                        $template->setValue('fornecedor_telefone_afiliado_2', $sale->supplier->affiliated_two_phone); //@phpstan-ignore-line
                        $template->setValue('fornecedor_descricao', $sale->supplier->description); //@phpstan-ignore-line

                        //Substitui os placeholders com os dados do endereco do fornecedor
                        $template->setValue('fornecedor_cep', $sale->supplier->address->cep); //@phpstan-ignore-line
                        $template->setValue('fornecedor_rua', $sale->supplier->address->street); //@phpstan-ignore-line
                        $template->setValue('fornecedor_numero', $sale->supplier->address->number); //@phpstan-ignore-line
                        $template->setValue('fornecedor_bairro', $sale->supplier->address->neighborhood); //@phpstan-ignore-line
                        $template->setValue('fornecedor_cidade', $sale->supplier->address->city->name); //@phpstan-ignore-line
                        $template->setValue('fornecedor_estado', $sale->supplier->address->state); //@phpstan-ignore-line
                        $template->setValue('fornecedor_complemento', $sale->supplier->address->complement); //@phpstan-ignore-line

                        //Substitui os placeholders com os dados da venda
                        $template->setValue('metodo_de_pagamento', $sale->payment_method); //@phpstan-ignore-line
                        $template->setValue('status', $sale->status); //@phpstan-ignore-line
                        $template->setValue('data_venda', Carbon::parse($sale->date_sale)->format('d/m/Y')); //@phpstan-ignore-line
                        $template->setValue('data_pagamento', Carbon::parse($sale->date_payment)->format('d/m/Y')); //@phpstan-ignore-line
                        $template->setValue('desconto', $sale->discount); //@phpstan-ignore-line
                        $template->setValue('acrescimo', $sale->surcharge); //@phpstan-ignore-line
                        $template->setValue('entrada', $sale->down_payment); //@phpstan-ignore-line
                        $template->setValue('numero_parcelas', $sale->number_installments); //@phpstan-ignore-line
                        $template->setValue('reembolso', $sale->reimbursement); //@phpstan-ignore-line
                        $template->setValue('data_cancelamento', Carbon::parse($sale->date_cancel)->format('d/m/Y')); //@phpstan-ignore-line
                        $template->setValue('total', number_format($sale->total, 2, ',', '.')); //@phpstan-ignore-line

                        if ($sale->number_installments > 1) { //@phpstan-ignore-line
                            for ($i = 0; $i < $sale->number_installments; $i++) {
                                $template->setValue("parcela_{$i}_data_vencimento", Carbon::parse($sale->paymentInstallments[$i]->due_date)->format('d/m/Y')); //@phpstan-ignore-line
                                $template->setValue("parcela_{$i}_valor", number_format($sale->paymentInstallments[$i]->value, 2, ',', '.')); //@phpstan-ignore-line
                                $template->setValue("parcela_{$i}_status", $sale->paymentInstallments[$i]->status); //@phpstan-ignore-line
                                $template->setValue("parcela_{$i}_data_pagamento", Carbon::parse($sale->paymentInstallments[$i]->due_date)->format('d/m/Y')); //@phpstan-ignore-line
                                $template->setValue("parcela_{$i}_valor_pagamento", number_format($sale->paymentInstallments[$i]->payment_value, 2, ',', '.')); //@phpstan-ignore-line
                                $template->setValue("parcela_{$i}_metodo_pagamento", $sale->paymentInstallments[$i]->payment_method); //@phpstan-ignore-line
                                $template->setValue("parcela_{$i}_discount", number_format($sale->paymentInstallments[$i]->discount, 2, ',', '.')); //@phpstan-ignore-line
                                $template->setValue("parcela_{$i}_surcharge", number_format($sale->paymentInstallments[$i]->surcharge, 2, ',', '.')); //@phpstan-ignore-line
                            }
                        }

                        // Salva o contrato preenchido
                        $caminhoContratoPreenchido = "storage/contracts/contrato_{$sale->client->name}.docx"; //@phpstan-ignore-line
                        $template->saveAs($caminhoContratoPreenchido);

                        // Retorna uma resposta ou faz o download do arquivo
                        return response()->download($caminhoContratoPreenchido)->deleteFileAfterSend(true);
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
