<?php

namespace App\Filament\Widgets;

use App\Models\{Sale, User};
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Cards extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()), // @phpstan-ignore-line
            Stat::make(__('Total Sales'), $this->loadSaleFiltersAndQuery()),
        ];
    }

    private function loadSaleFiltersAndQuery(): float
    {
        return Sale::query()
            ->when($this->filters['start_date'], fn ($query) => $query->where('date_sale', '>', $this->filters['start_date']))
            ->when($this->filters['end_date'], fn ($query) => $query->where('date_sale', '<', $this->filters['end_date']))
            ->sum('total');
    }
}
