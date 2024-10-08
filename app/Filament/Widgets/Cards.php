<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use App\Models\{Sale, User, VehicleType};
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
            Stat::make(__('Total Users'), User::count()), // @phpstan-ignore-line
            Stat::make(__('Total Sales'), $this->loadSaleFiltersAndQuery()->count())
                 ->description('R$ ' . number_format($this->loadSaleFiltersAndQuery()->sum('total'), 2, ',', '.')),
        ];

        foreach ($this->vehiclesTypeSale() as $value) {
            if ($value['count'] > 0) {
                $stats[] = Stat::make(__('Total Sales') . ' (' . $value["type"] . ')', $value['count'])->description('R$ ' . number_format($value['total_value'], 2, ',', '.'));
            }
        }

        return $stats;
    }

    /**
     * Obtém as vendas de veículos agrupadas por tipo.
     *
     * @return array<int, array{type: string, count: int, total_value: float}>
     */
    private function vehiclesTypeSale(): array
    {
        // Obter todos os tipos de veículos
        $types = VehicleType::get(['id', 'name'])->toArray(); //@phpstan-ignore-line

        // Preparar o resultado final
        $salesByType = [];

        // Iterar sobre cada tipo de veículo
        foreach ($types as $type) {
            // Obter os veículos vendidos de cada tipo
            $vehicles = Sale::query()
                ->whereHas('vehicle', function ($query) use ($type) {
                    // Aplicar o filtro de data de venda e tipo de veículo nos veículos
                    $query->when($this->filters['start_date'], fn ($query) => $query->where('sold_date', '>', $this->filters['start_date']))
                        ->when($this->filters['end_date'], fn ($query) => $query->where('sold_date', '<', $this->filters['end_date']))
                        ->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $type['id']));
                })
                ->get();
            Vehicle::query()
                ->whereNotNull('sold_date') // Somente veículos vendidos
                ->when($this->filters['start_date'], fn ($query) => $query->where('sold_date', '>', $this->filters['start_date']))
                ->when($this->filters['end_date'], fn ($query) => $query->where('sold_date', '<', $this->filters['end_date']))
                ->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $type['id']))
                ->get();

            // Adicionar ao array de resultados
            $salesByType[] = [
                'type'        => $type['name'],
                'count'       => $vehicles->count(),
                'total_value' => $vehicles->sum('total'),
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
