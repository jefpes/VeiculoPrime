<?php

namespace App\Filament\Resources;

use App\Enums\{PaymentMethod, StatusPayments};
use App\Filament\Resources\PaymentInstallmentsResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Models\PaymentInstallments;
use Carbon\Carbon;
use Filament\Forms\Components\{DatePicker, Select};
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder};
use Illuminate\Support\Facades\Auth;

class PaymentInstallmentsResource extends Resource
{
    protected static ?string $model = PaymentInstallments::class;

    protected static ?int $navigationSort = 1;

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('sale_id')
                    ->relationship('sale', 'id')
                    ->required(),
                Forms\Components\DatePicker::make('due_date')
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('payment_date'),
                Forms\Components\TextInput::make('payment_value')
                    ->numeric(),
                Forms\Components\TextInput::make('payment_method')
                    ->maxLength(255),
                Forms\Components\TextInput::make('discount')
                    ->numeric(),
                Forms\Components\TextInput::make('surcharge')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sale.client.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('value')
                    ->numeric()
                    ->sortable()
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDENTE'    => 'info',
                        'REEMBOLSADO' => 'warning',
                        'CANCELADO'   => 'danger',
                        default       => 'success',
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable()
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('payment_value')
                    ->numeric()
                    ->sortable()
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('surcharge')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('receive')
                    ->translateLabel()
                    ->icon('heroicon-o-banknotes')
                    ->slideOver()
                    ->requiresConfirmation()
                    ->modalHeading(__('Receive installment'))
                    ->modalDescription(null)
                    ->modalIcon('heroicon-o-banknotes')
                    ->fillForm(fn (PaymentInstallments $record): array => [
                        'value'        => $record->value, // @phpstan-ignore-line
                        'payment_date' => now(),
                    ])
                    ->form([
                        MoneyInput::make('value')->readOnly(),
                        Forms\Components\Select::make('payment_method')
                            ->options(
                                collect(PaymentMethod::cases())
                                    ->mapWithKeys(fn (PaymentMethod $type) => [$type->value => ucfirst($type->value)])
                            )
                            ->required(),
                        MoneyInput::make('payment_value')->required(),
                        Forms\Components\DatePicker::make('payment_date')->required(),
                    ])->action(function (PaymentInstallments $installment, array $data) {
                        $installment->update([
                            'user_id'        => Auth::id(),
                            'status'         => 'PAGO',
                            'discount'       => $data['payment_value'] < $installment->value ? ($installment->value - $data['payment_value']) : 0, //@phpstan-ignore-line
                            'surcharge'      => $data['payment_value'] > $installment->value ? ($data['payment_value'] - $installment->value) : 0,
                            'payment_date'   => $data['payment_date'],
                            'payment_value'  => $data['payment_value'],
                            'payment_method' => $data['payment_method'],
                        ]);
                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Installment received'))
                            ->send();
                    })->visible(fn (PaymentInstallments $installment): bool => $installment->status === 'PENDENTE'), //@phpstan-ignore-line
                Tables\Actions\Action::make('refund')
                    ->translateLabel()
                    ->icon('heroicon-o-receipt-refund')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (PaymentInstallments $installment) {
                        $installment->update([
                            'user_id'        => Auth::id(),
                            'status'         => 'PENDENTE',
                            'discount'       => null,
                            'surcharge'      => null,
                            'payment_date'   => null,
                            'payment_value'  => null,
                            'payment_method' => null,
                        ]);
                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Installment refunded'))
                            ->send();
                    })->visible(fn (PaymentInstallments $installment): bool => $installment->status === 'PAGO'), //@phpstan-ignore-line
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
                Filter::make('client')
                    ->form([
                        Select::make('client')
                            ->searchable()
                            ->options(function () {
                                return \App\Models\Client::all()->mapWithKeys(function ($client) {
                                    return [
                                        $client->id => "{$client->name}", //@phpstan-ignore-line
                                    ];
                                });
                            }),
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
                            $modelName = \App\Models\Client::find($data['client'])->name ?? null; //@phpstan-ignore-line

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
