<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleExpenseResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Models\{Vehicle, VehicleExpense};
use Carbon\Carbon;
use Filament\Forms\Components\{DatePicker, Group, Select};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class VehicleExpenseResource extends Resource
{
    protected static ?string $model = VehicleExpense::class;

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('Vehicle');
    }

    public static function getModelLabel(): string
    {
        return __('Expense');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Expenses');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vehicle_id')
                        ->relationship('vehicle', 'id')
                        ->options(function () {
                            return Vehicle::get()->mapWithKeys(function (Vehicle $vehicle) { //@phpstan-ignore-line
                                return [
                                    $vehicle->id => "{$vehicle->plate} - {$vehicle->model->name} ({$vehicle->year_one}/{$vehicle->year_two})", //@phpstan-ignore-line
                                ];
                            });
                        })
                        ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                MoneyInput::make('value')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(255),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle')
                    ->getStateUsing(fn (VehicleExpense $record) => $record->vehicle->plate . ' - ' . $record->vehicle->model->name) //@phpstan-ignore-line
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->money('BRL')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date_expense')
                    ->form([
                        Group::make([
                            DatePicker::make('expense_date_initial')->label('Expense date after')->default(now()->subMonth()->format('Y-m-d')),
                            DatePicker::make('expense_date_final')->label('Expense date before')->default(now()->format('Y-m-d')),
                        ])->columns(2),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['expense_date_initial'], fn ($query, $value) => $query->where('date', '>=', $value))
                            ->when($data['expense_date_final'], fn ($query, $value) => $query->where('date', '<=', $value));
                    })->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['expense_date_initial'] ?? null) {
                            $indicators[] = __('Expense date after') . ': ' . Carbon::parse($data['expense_date_initial'])->format('d/m/Y');
                        }

                        if ($data['expense_date_final'] ?? null) {
                            $indicators[] = __('Expense date before') . ': ' . Carbon::parse($data['expense_date_final'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
                Filter::make('value_expense')
                    ->form([
                        Group::make([
                            MoneyInput::make('value_expense_min')->label('Value expense min'),
                            MoneyInput::make('value_expense_max')->label('Value expense max'),
                        ])->columns(2),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['value_expense_min'], fn ($query, $value) => $query->where('value', '>=', $value))
                            ->when($data['value_expense_max'], fn ($query, $value) => $query->where('value', '<=', $value));
                    })->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['value_expense_min'] ?? null) {
                            $indicators[] = __('Value expense min') . ': ' . number_format($data['value_expense_min'], 2, ',', '');
                        }

                        if ($data['value_expense_max'] ?? null) {
                            $indicators[] = __('Value expense max') . ': ' . number_format($data['value_expense_max'], 2, ',', '');
                        }

                        return $indicators;
                    }),
                Filter::make('model')
                    ->form([
                        Select::make('model')
                            ->label('Vehicle model')
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
            ])
            ->actions([
                Tables\Actions\EditAction::make()->after(
                    function ($record) {
                        $record->update(['user_id' => Auth::id()]);
                    }
                ),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVehicleExpenses::route('/'),
        ];
    }
}
