<?php

namespace App\Filament\Widgets;

use App\Enums\Permission;
use App\Models\{User, Vehicle, VehicleType};
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ExpenseCards extends BaseWidget
{
    use InteractsWithPageFilters;

    public static function canView(): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->hasAbility(Permission::VEHICLE_EXPENSE_READ->value);
    }

    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $stats[] = Stat::make(__('Total Expenses'), 'R$ ' . number_format($this->totalExpensesByType()['total_expenses'], 2, ',', '.'));

        foreach ($this->totalExpensesByType()['expenses_by_type'] as $value) {
            $stats[] = Stat::make(__('Expense') . ' (' . $value["type"] . ')', 'R$ ' . number_format($value['total_expenses'], 2, ',', '.'));
        }

        return $stats;
    }

    /**
     * Retorna o total de despesas e também as despesas por tipo de veículo,
     * com a opção de aplicar filtros de tempo.
     *
     * @return array{total_expenses: float, expenses_by_type: array<int, array{type: string, total_expenses: float}>}
     */
    private function totalExpensesByType(): array
    {
        // Obter todos os tipos de veículos
        $types = VehicleType::get(['id', 'name'])->toArray(); //@phpstan-ignore-line

        // Inicializar o total de todas as despesas
        $totalExpenses = 0;

        // Preparar o array final com despesas por tipo
        $expensesByType = [];

        // Iterar sobre cada tipo de veículo
        foreach ($types as $type) {
            // Obter as despesas para os veículos de cada tipo
            $expenses = Vehicle::query() //@phpstan-ignore-line
                ->with(['expenses' => function ($query) {
                    // Filtrar as despesas por data
                    $query->when($this->filters['start_date'], fn ($query) => $query->where('date', '>=', $this->filters['start_date']))
                        ->when($this->filters['end_date'], fn ($query) => $query->where('date', '<=', $this->filters['end_date']));
                }])
                ->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $type['id'])) // Filtro pelo tipo de veículo
                ->get()
                ->flatMap(fn ($vehicle) => $vehicle->expenses) //@phpstan-ignore-line
                ->sum('value'); // Somar os valores das despesas

            // Acumular o total de todas as despesas
            $totalExpenses += $expenses;

            // Adicionar ao array de despesas por tipo
            $expensesByType[] = [
                'type'           => $type['name'],   // Nome do tipo de veículo
                'total_expenses' => $expenses,       // Total de despesas para o tipo de veículo
            ];
        }

        return [
            'total_expenses'   => $totalExpenses,   // Total geral de despesas
            'expenses_by_type' => $expensesByType,  // Despesas agrupadas por tipo
        ];
    }
}
