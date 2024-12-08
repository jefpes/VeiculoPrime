<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\Permission;
use App\Models\{User, Vehicle, VehicleType};
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PurchaseCards extends BaseWidget
{
    use InteractsWithPageFilters;

    public static function canView(): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->hasAbility(Permission::VEHICLE_READ->value);
    }

    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $stats[] = Stat::make(__('Total Purchase'), Vehicle::query()->when($this->filters['start_date'], fn ($query) => $query->where('purchase_date', '>', $this->filters['start_date']))->when($this->filters['end_date'], fn ($query) => $query->where('purchase_date', '<', $this->filters['end_date']))->count())
                ->description('R$ ' . number_format(Vehicle::query()->when($this->filters['start_date'], fn ($query) => $query->where('purchase_date', '>', $this->filters['start_date']))->when($this->filters['end_date'], fn ($query) => $query->where('purchase_date', '<', $this->filters['end_date']))->sum('purchase_price'), 2, ',', '.'));

        foreach ($this->vehiclesTypePurchase() as $value) {
            $stats[] = Stat::make(__('Purchases') . ' (' . $value["type"] . ')', $value['count'])->description('R$ ' . number_format($value['total_by_type'], 2, ',', '.'));
        }

        return $stats;
    }

    /**
     * Obtém as vendas de veículos agrupadas por tipo.
     *
     * @return array<int, array{type: string, count: int, total_by_type: float}>
     */
    private function vehiclesTypePurchase(): array
    {
        // Obter todos os tipos de veículos
        $types = VehicleType::get(['id', 'name'])->toArray(); //@phpstan-ignore-line

        // Preparar o resultado final
        $puchaseByType = [];

        // Iterar sobre cada tipo de veículo
        foreach ($types as $type) {
            // Obter as vendas por tipo de veículo e aplicar os filtros de data
            $vehicles = Vehicle::query()
                ->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $type['id']))
                ->when($this->filters['start_date'], fn ($query) => $query->where('purchase_date', '>', $this->filters['start_date']))
                ->when($this->filters['end_date'], fn ($query) => $query->where('purchase_date', '<', $this->filters['end_date']))
                ->get();

            // Adicionar ao array de resultados por tipo de veículo
            $puchaseByType[] = [
                'type'          => $type['name'],      // Nome do tipo de veículo
                'count'         => $vehicles->count(),    // Quantidade de veículos vendidos
                'total_by_type' => $vehicles->sum('purchase_price'),        // Valor total das vendas
            ];
        }

        return $puchaseByType;
    }
}
