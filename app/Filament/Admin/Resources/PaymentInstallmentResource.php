<?php

namespace App\Filament\Admin\Resources;

use App\Enums\{StatusPayments};
use App\Filament\Admin\Resources\PaymentInstallmentResource\{Pages};
use App\Forms\Components\{MoneyInput, SelectPaymentMethod};
use App\Models\{Company, PaymentInstallment};
use App\Tools\Contracts;
use Carbon\Carbon;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\{DatePicker, Group, Select};
use Filament\Forms\{Get, Set};
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Summarizers\{Average, Sum};
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder};
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;

class PaymentInstallmentResource extends Resource
{
    protected static ?string $model = PaymentInstallment::class;

    protected static ?int $navigationSort = 14;

    public static function getNavigationGroup(): ?string
    {
        return __('Financial');
    }

    public static function getModelLabel(): string
    {
        return __('Installment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Installments');
    }

    public static function updatePaymentValue(Set $set, Get $get): void
    {
        $initialValue = $get('value');
        $lateFee      = $get('late_fee') ?: 0;
        $interestRate = $get('interest_rate') ?: 0;

        // Converta due_date e payment_date para instâncias de Carbon
        $dueDate     = $get('due_date') ? Carbon::parse($get('due_date')) : null;
        $paymentDate = $get('payment_date') ? Carbon::parse($get('payment_date')) : null;

        // Verifique se as datas estão válidas
        if (!$dueDate || !$paymentDate) {
            // Se as datas não forem válidas, defina interest e payment_value como 0
            $set('interest', 0);
            $set('payment_value', $initialValue);

            return;
        }

        // Calcula a diferença de dias entre a data de vencimento e a data de pagamento
        $daysLate = $dueDate->diffInDays($paymentDate, false); // `false` para considerar valores negativos

        // Se for pagamento antecipado (daysLate negativo), zera o daysLate
        if ($daysLate < 0) {
            $daysLate = 0;
        }

        // Juros composto diário: Valor inicial * (1 + taxa de juros / 100) ^ dias
        $interest = $initialValue * pow((1 + ($interestRate / 100)), $daysLate) - $initialValue;

        // Calcula o valor total de pagamento
        $paymentValue = $initialValue + $interest - ($get('discount') ?: 0);

        if ($daysLate > 0) {
            $paymentValue = $paymentValue + $lateFee;
        }

        // Atualiza os valores dos campos reativos
        $set('interest', round($interest, 2));
        $set('payment_value', round($paymentValue, 2));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth_user()->tenant_id === null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sale.client.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('value')
                    ->numeric()
                    ->sortable()
                    ->money('BRL')
                    ->summarize(Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function (string $state, $record): string {
                        // Se o status for 'PENDENTE', verificar se há parcelas em atraso
                        if ($state === 'PENDENTE') {
                            if ($record->due_date < now() && $record->status === 'PENDENTE') {
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
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable()
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('late_fee')
                    ->sortable()
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->summarize(Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('interest_rate')
                    ->numeric()
                    ->sortable()
                    ->suffix('%')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->summarize(Average::make()->suffix('%')),
                Tables\Columns\TextColumn::make('interest')
                    ->sortable()
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->summarize(Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('payment_value')
                    ->sortable()
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->summarize(Sum::make()->money('BRL')),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Sum::make()->money('BRL')),
            ])
            ->actions([
                Tables\Actions\Action::make('receive')
                    ->authorize('receive')
                    ->translateLabel()
                    ->icon('heroicon-o-banknotes')
                    ->slideOver()
                    ->requiresConfirmation()
                    ->modalHeading(__('Receive installment'))
                    ->modalDescription(null)
                    ->modalIcon('heroicon-o-banknotes')
                    ->fillForm(fn (PaymentInstallment $record): array => [
                        'value'         => $record->value,
                        'payment_value' => $record->value,
                        'due_date'      => $record->due_date,
                        'payment_date'  => now(),
                        'late_fee'      => Company::query()->first()->late_fee,
                        'interest_rate' => Company::query()->first()->interest_rate_installment,
                    ])
                    ->form([
                        SelectPaymentMethod::make('payment_method')->required(),
                        Forms\Components\DatePicker::make('due_date')->readOnly(),
                        Group::make([
                            MoneyInput::make('value')->readOnly(),
                            MoneyInput::make('discount')
                                ->live(debounce: 1000)
                                ->afterStateUpdated(fn ($set, $get) => self::updatePaymentValue($set, $get)),
                        ])->columns(2),
                        Group::make([
                            MoneyInput::make('late_fee')
                                ->live(debounce: 1000)
                                ->afterStateUpdated(fn ($set, $get) => self::updatePaymentValue($set, $get)),
                            MoneyInput::make('interest_rate')
                                ->prefix(null)
                                ->suffix('%')
                                ->live(debounce: 1000)
                                ->afterStateUpdated(fn ($set, $get) => self::updatePaymentValue($set, $get)),
                        ])->columns(2),
                        Group::make([
                            MoneyInput::make('interest')->readOnly(),
                            MoneyInput::make('payment_value')->readOnly(),
                        ])->columns(2),
                        Forms\Components\DatePicker::make('payment_date')->required()
                            ->live(debounce: 1000)
                            ->afterStateUpdated(fn ($set, $get) => self::updatePaymentValue($set, $get)),
                    ])->action(function (PaymentInstallment $installment, array $data) {
                        $installment->update([
                            'user_id'        => Auth::id(),
                            'status'         => 'PAGO',
                            'discount'       => $data['discount'],
                            'late_fee'       => $data['late_fee'],
                            'interest_rate'  => $data['interest_rate'],
                            'interest'       => $data['interest'],
                            'payment_date'   => $data['payment_date'],
                            'payment_value'  => $data['payment_value'],
                            'payment_method' => $data['payment_method'],
                        ]);
                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Installment received'))
                            ->send();
                    }),
                Tables\Actions\Action::make('refund')
                    ->authorize('refund')
                    ->translateLabel()
                    ->icon('heroicon-o-receipt-refund')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (PaymentInstallment $installment) {
                        $installment->update([
                            'user_id'        => Auth::id(),
                            'status'         => 'PENDENTE',
                            'discount'       => null,
                            'late_fee'       => null,
                            'interest_rate'  => null,
                            'interest'       => null,
                            'payment_date'   => null,
                            'payment_value'  => null,
                            'payment_method' => null,
                        ]);
                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Installment refunded'))
                            ->send();
                    }),
                Tables\Actions\Action::make('receipt')
                    ->authorize('receipt')
                    ->requiresConfirmation()
                    ->modalHeading(__('Receipt'))
                    ->label('Receipt')
                    ->translateLabel()
                    ->icon('heroicon-o-document')
                    ->iconSize('md')
                    ->color('info')
                    ->form([
                        FileUpload::make('receipt')
                            ->label('Contract')
                            ->panelAspectRatio('2:1')
                            ->storeFiles(false)
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ]),
                    ])
                    ->action(function (array $data, PaymentInstallment $installment) {

                        $template = new TemplateProcessor($data['receipt']->getRealPath());

                        $caminho = Contracts::generateReceiptContract($template, $installment);

                        return response()->download($caminho)->deleteFileAfterSend(true);
                    }),
            ])
            ->filters([
                Filter::make('date_sale')
                    ->form([
                        Forms\Components\Group::make([
                            DatePicker::make('due_date_initial')->label('From')->default(now()->subMonth()->format('Y-m-d')),
                            DatePicker::make('due_date_final')->label('To')->default(now()->format('Y-m-d')),
                        ])->columns(2),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['due_date_initial'], fn ($query, $value) => $query->where('due_date', '>=', $value))
                            ->when($data['due_date_final'], fn ($query, $value) => $query->where('due_date', '<=', $value));
                    })->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['due_date_initial'] ?? null) {
                            $indicators[] = __('Due date after') . ': ' . Carbon::parse($data['due_date_initial'])->format('d/m/Y');
                        }

                        if ($data['due_date_final'] ?? null) {
                            $indicators[] = __('Due date before') . ': ' . Carbon::parse($data['due_date_final'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
                Filter::make('payment_method')
                    ->form([
                        SelectPaymentMethod::make('payment_method'),
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
                        ->options(StatusPayments::class),
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
                Filter::make('client')
                    ->form([
                        Select::make('client')
                            ->searchable()
                            ->options(fn () => \App\Models\People::query()->where('client', true)->get()->pluck('name', 'id')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['client'])) {
                            return $query->whereHas('sale', function ($query) use ($data) {
                                $query->where('client_id', $data['client']);
                            });
                        }

                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (!empty($data['client'])) {
                            $modelName = \App\Models\People::find($data['client'])->name ?? null; //@phpstan-ignore-line

                            if ($modelName) {
                                $indicators[] = __('Client') . ': ' . $modelName;
                            }
                        }

                        return $indicators;
                    }),
            ], layout: FiltersLayout::Modal);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePaymentInstallments::route('/'),
        ];
    }
}
