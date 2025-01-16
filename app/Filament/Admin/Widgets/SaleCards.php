<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\Permission;
use App\Models\{Sale, User, VehicleExpense, VehicleType};
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SaleCards extends BaseWidget
{
    use InteractsWithPageFilters;

    public static function canView(): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->hasAbility(Permission::SALE_READ->value);
    }

    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $stats[] = Stat::make(__('Total Sales'), $this->loadSaleFiltersAndQuery()->count())
                ->description('R$ ' . number_format($this->loadSaleFiltersAndQuery()->sum('total'), 2, ',', '.'));

        foreach ($this->vehiclesTypeSale() as $value) {
            $stats[] = Stat::make(__('Total Sales') . ' (' . $value["type"] . ')', $value['count'])->description('R$ ' . number_format($value['total_value'], 2, ',', '.'));
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
                $vehicle = $sale->vehicle;

                // Calcular o valor da venda
                $salePrice = $sale->total;

                // Obter o preço de compra do veículo
                $purchasePrice = $vehicle->purchase_price ?? 0;

                // Calcular as despesas associadas ao veículo
                $expenses = VehicleExpense::query()->where('vehicle_id', $vehicle->id)->get()->sum('value'); // Assumindo que existe uma relação de `expenses` no modelo Vehicle

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

    private function loadSaleFiltersAndQuery(): Builder
    {
        return Sale::query()
            ->when($this->filters['start_date'], fn ($query) => $query->where('date_sale', '>', $this->filters['start_date']))
            ->when($this->filters['end_date'], fn ($query) => $query->where('date_sale', '<', $this->filters['end_date']));
    }
}
