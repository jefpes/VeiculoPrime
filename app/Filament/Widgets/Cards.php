<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use App\Models\{Sale, VehicleType};
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class Cards extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $stats = [
            Stat::make(__('Total Sales'), $this->loadSaleFiltersAndQuery()->count())
                ->description('R$ ' . number_format($this->loadSaleFiltersAndQuery()->sum('total'), 2, ',', '.')),
        ];

        foreach ($this->vehiclesTypeSale() as $value) {
            if ($value['count'] > 0) {
                $stats[] = Stat::make(__('Total Sales') . ' (' . $value["type"] . ')', $value['count'])->description('R$ ' . number_format($value['total_value'], 2, ',', '.'));
            }
        }

        $stats[] = Stat::make(__('Profit'), 'R$ ' . number_format($this->calculateProfit(), 2, ',', '.'));

        foreach ($this->vehiclesTypeSale() as $value) {
            if ($value['count'] > 0) {
                $stats[] = Stat::make(__('Profit') . ' (' . $value["type"] . ')', 'R$ ' . number_format($value['profit'], 2, ',', '.'));
            }
        }

        if ($this->totalExpensesByType()['total_expenses'] > 0) {
            $stats[] = Stat::make(__('Total Expenses'), 'R$ ' . number_format($this->totalExpensesByType()['total_expenses'], 2, ',', '.'));
        }

        foreach ($this->totalExpensesByType()['expenses_by_type'] as $value) {
            if ($value['total_expenses'] > 0) {
                $stats[] = Stat::make(__('Expense') . ' (' . $value["type"] . ')', 'R$ ' . number_format($value['total_expenses'], 2, ',', '.'));
            }
        }

        return $stats;
    }

    /**
     * Obtém as vendas de veículos agrupadas por tipo.
     *
     * @return array<int, array{type: string, count: int, total_value: float, profit: float}>
     */
    private function vehiclesTypeSale(): array
    {
        // Obter todos os tipos de veículos
        $types = VehicleType::get(['id', 'name'])->toArray(); //@phpstan-ignore-line

        // Preparar o resultado final
        $salesByType = [];

        // Iterar sobre cada tipo de veículo
        foreach ($types as $type) {
            // Obter as vendas por tipo de veículo e aplicar os filtros de data
            $sales = Sale::query()
                ->whereHas('vehicle', function ($query) use ($type) {
                    $query->when($this->filters['start_date'], fn ($query) => $query->where('sold_date', '>', $this->filters['start_date']))
                          ->when($this->filters['end_date'], fn ($query) => $query->where('sold_date', '<', $this->filters['end_date']))
                          ->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $type['id']));
                })
                ->get();

            $totalSales  = 0;
            $totalProfit = 0;

            foreach ($sales as $sale) {
                // Obter o veículo associado à venda
                $vehicle = $sale->vehicle; //@phpstan-ignore-line

                // Calcular o valor da venda
                $salePrice = $sale->total; //@phpstan-ignore-line

                // Obter o preço de compra do veículo
                $purchasePrice = $vehicle->purchase_price ?? 0;

                // Calcular as despesas associadas ao veículo
                $expenses = $vehicle->expenses()->sum('value'); // Assumindo que o relacionamento `expenses` existe no modelo Vehicle

                // Calcular o lucro (venda - (custo de compra + despesas))
                $profit = $salePrice - ($purchasePrice + $expenses);

                // Acumular o total de vendas e lucro
                $totalSales += $salePrice;
                $totalProfit += $profit;
            }

            // Adicionar ao array de resultados por tipo de veículo
            $salesByType[] = [
                'type'        => $type['name'],      // Nome do tipo de veículo
                'count'       => $sales->count(),    // Quantidade de veículos vendidos
                'total_value' => $totalSales,        // Valor total das vendas
                'profit'      => $totalProfit,       // Lucro total
            ];
        }

        return $salesByType;
    }

    private function calculateProfit(): float
    {
        // Filtrar as vendas de acordo com os parâmetros de data
        $sales = Sale::query()
            ->when($this->filters['start_date'], fn ($query) => $query->where('date_sale', '>', $this->filters['start_date']))
            ->when($this->filters['end_date'], fn ($query) => $query->where('date_sale', '<', $this->filters['end_date']))
            ->get();

        $totalProfit = 0;

        foreach ($sales as $sale) {
            // Obter o veículo relacionado à venda
            $vehicle = $sale->vehicle; //@phpstan-ignore-line

            // Preço de compra do veículo
            $purchasePrice = $vehicle->purchase_price ?? 0;

            // Total de despesas associadas ao veículo
            $expenses = $vehicle->expenses()->sum('value'); // Assumindo que existe uma relação de `expenses` no modelo Vehicle

            // Preço de venda do veículo
            $salePrice = $sale->total; //@phpstan-ignore-line

            // Calcular o lucro
            $profit = $salePrice - ($purchasePrice + $expenses);

            // Acumular no total de lucro
            $totalProfit += $profit;
        }

        return $totalProfit;
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
            // Obter as despesas para os veículos vendidos de cada tipo
            $expenses = Vehicle::query() //@phpstan-ignore-line
                ->when($this->filters['start_date'], fn ($query) => $query->where('sold_date', '>', $this->filters['start_date']))
                ->when($this->filters['end_date'], fn ($query) => $query->where('sold_date', '<', $this->filters['end_date']))
                ->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $type['id']))
                ->with('expenses') // Carregar as despesas relacionadas aos veículos
                ->get()
                ->flatMap(fn ($vehicle) => $vehicle->expenses) //@phpstan-ignore-line Acessar as despesas de cada veículo
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

    private function loadSaleFiltersAndQuery(): Builder
    {
        return Sale::query()
            ->when($this->filters['start_date'], fn ($query) => $query->where('date_sale', '>', $this->filters['start_date']))
            ->when($this->filters['end_date'], fn ($query) => $query->where('date_sale', '<', $this->filters['end_date']));
    }
}
