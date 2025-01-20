<?php

namespace App\Filament\Master\Resources;

use App\Filament\Master\Resources\VehicleExpenseResource\{Pages};
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
use Leandrocfe\FilamentPtbrFormFields\Money;

class VehicleExpenseResource extends Resource
{
    protected static ?string $model = VehicleExpense::class;

    protected static ?int $navigationSort = 33;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-down';

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
                                    $vehicle->id => "{$vehicle->plate} - {$vehicle->model->name} ({$vehicle->year_one}/{$vehicle->year_two})",
                                ];
                            });
                        })
                        ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Money::make('value')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(255),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('date', 'desc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.plate')
                    ->label('Plate')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vehicle.model.name')
                    ->label('Model')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Filter::make('filter')
        ->form([
            Group::make([
                // Date filters
                DatePicker::make('expense_date_initial')->label('Expense date after'),
                DatePicker::make('expense_date_final')->label('Expense date before'),
            ])->columns(2),

            Group::make([
                // Value filters
                Money::make('value_expense_min')
                    ->afterStateUpdated(
                        fn ($set, $get) => $set('value_expense_min', number_format((float)string_money_to_float($get('value_expense_min')), 2, ',', ''))
                    )
                    ->live(debounce: 1000),
                Money::make('value_expense_max')
                    ->afterStateUpdated(
                        fn ($set, $get) => $set('value_expense_max', number_format((float)string_money_to_float($get('value_expense_max')), 2, ',', ''))
                    )
                    ->live(debounce: 1000),
            ])->columns(2),

            // Model filter
            Select::make('model')
                ->label('Vehicle model')
                ->searchable()
                ->options(fn () => \App\Models\VehicleModel::all()->mapWithKeys(fn ($model) => [
                    $model->id => "{$model->name}",
                ])),
        ])
        ->query(function (Builder $query, array $data): Builder {
            // Filtering by dates
            $query->when($data['expense_date_initial'], fn ($query) => $query->where('date', '>=', $data['expense_date_initial']));
            $query->when($data['expense_date_final'], fn ($query) => $query->where('date', '<=', $data['expense_date_final']));

            // Filtering by values
            $query->when($data['value_expense_min'] > 0 && $data['value_expense_min'] !== "0,00", fn ($query) => $query->where('value', '>=', $data['value_expense_min']));
            $query->when($data['value_expense_max'] > 0 && $data['value_expense_max'] !== "0,00", fn ($query) => $query->where('value', '<=', $data['value_expense_max']));

            // Filtering by model
            if (!empty($data['model'])) {
                $query->whereHas('vehicle', fn ($query) => $query->where('vehicle_model_id', $data['model']));
            }

            return $query;
        })
        ->indicateUsing(function (array $data): array {
            $indicators = [];

            // Indicators for date filters
            if ($data['expense_date_initial'] ?? null) {
                $indicators[] = __('Expense date after') . ': ' . Carbon::parse($data['expense_date_initial'])->format('d/m/Y');
            }

            if ($data['expense_date_final'] ?? null) {
                $indicators[] = __('Expense date before') . ': ' . Carbon::parse($data['expense_date_final'])->format('d/m/Y');
            }

            // Indicators for value filters
            if ($data['value_expense_min'] !== "0,00" && $data['value_expense_min'] !== null) {
                $indicators[] = __('Value expense min') . ': ' . number_format((float)$data['value_expense_min'], 2, ',', '');
            }

            if ($data['value_expense_max'] !== "0,00" && $data['value_expense_max'] !== null) {
                $indicators[] = __('Value expense max') . ': ' . number_format((float)$data['value_expense_max'], 2, ',', '');
            }

            // Indicator for model filter
            if (!empty($data['model'])) {
                $modelName = \App\Models\VehicleModel::query()->find($data['model'])->name ?? null;

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
