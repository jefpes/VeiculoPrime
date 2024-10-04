<?php

namespace App\Filament\Resources;

use App\Enums\{PaymentMethod, StatusPayments};
use App\Filament\Resources\PaymentInstallmentsResource\{Pages};
use App\Models\PaymentInstallments;
use Carbon\Carbon;
use Filament\Forms\Components\{DatePicker, Select};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder};

class PaymentInstallmentsResource extends Resource
{
    protected static ?string $model = PaymentInstallments::class;

    // public static function getNavigationGroup(): ?string
    // {
    //     return __('Financial');
    // }

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
            ->filters([
                Filter::make('date_sale')
                    ->form([
                        Forms\Components\Group::make([
                            DatePicker::make('due_date_initial')->label('From'),
                            DatePicker::make('due_date_final')->label('To'),
                        ])->columns(2),
                        // DatePicker::make('due_date_initial')->label('Due date after'),
                        // DatePicker::make('due_date_final')->label('Due date before'),
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
