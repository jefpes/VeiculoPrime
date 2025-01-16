<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\Permission;
use App\Models\{User, Vehicle, VehicleType};
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StockCards extends BaseWidget
{
    use InteractsWithPageFilters;

    public static function canView(): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->hasAbility(Permission::VEHICLE_EXPENSE_READ->value);
    }

    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $vStock = Vehicle::query()->whereNull('sold_date');

        $stats[] = Stat::make(__('Total Stock'), $vStock->count() . ' - ' . 'R$ ' . number_format($vStock->sum('purchase_price'), 2, ',', '.'))->description(__('Sale price') . ': R$ ' . number_format($vStock->sum('sale_price'), 2, ',', '.'));

        foreach ($this->vehicleStock() as $value) {
            $stats[] = Stat::make(__('Stock') . ' (' . $value["type"] . ')', $value['count'] . ' - ' . 'R$ ' . number_format($value['total_stock_purchase'], 2, ',', '.'))->description(__('Sale price') . ': R$ ' . number_format($value['total_stock_sale'], 2, ',', '.'));
        }

        return $stats;
    }

    /**
     * Obtém as vendas de veículos agrupadas por tipo.
     *
     * @return array<int, array{type: string, count: int, total_stock_purchase: float, total_stock_sale: float}>
     */
    private function vehicleStock(): array
    {
        // Obter todos os tipos de veículos
        $types = VehicleType::get(['id', 'name'])->toArray(); //@phpstan-ignore-line

        // Preparar o resultado final
        $stockByType = [];

        // Iterar sobre cada tipo de veículo
        foreach ($types as $type) {
            // Obter as vendas por tipo de veículo e aplicar os filtros de data
            $vehicles = Vehicle::query()
                ->whereNull('sold_date')
                ->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $type['id']))
                ->get();

            $totalStock     = 0;
            $totalStockSale = 0;

            foreach ($vehicles as $v) {
                // Calcular o valor da venda
                $stockPrice = $v->purchase_price ?? 0;

                // Obter o preço de compra do veículo
                $salePrice = $v->sale_price ?? 0;

                // Acumular o total de vendas e lucro
                $totalStock += $stockPrice;
                $totalStockSale += $salePrice;
            }

            // Adicionar ao array de resultados por tipo de veículo
            $stockByType[] = [
                'type'                 => $type['name'],      // Nome do tipo de veículo
                'count'                => $vehicles->count(),    // Quantidade de veículos no stock
                'total_stock_purchase' => $totalStock,        // Valor total de compra do stock
                'total_stock_sale'     => $totalStockSale,       // Preço total de venda do stock
            ];
        }

        return $stockByType;
    }
}
