<?php

namespace App\Filament\Widgets;

use App\Models\{Sale, Vehicle, VehicleExpense};
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\{Trend, TrendValue};
use Illuminate\Contracts\Support\Htmlable;

class SalesGraphic extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '30s';

    public function getHeading(): string | Htmlable | null
    {
        return __('Last year');
    }

    public function getDescription(): ?string
    {
        return __('Purchases, sales and expenses, per month');
    }

    /**
     * @return int | string | array<string, int | null>
     */
    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    protected function getMaxHeight(): ?string
    {
        return '66vh';
    }

    protected function getData(): array
    {

        $sale = Trend::model(Sale::class)
                ->dateColumn('date_sale')
                ->between(
                    start: now()->subMonth(12), //@phpstan-ignore-line
                    end: now(),
                )
                ->perMonth()
                ->sum('total');

        $purchase = Trend::model(Vehicle::class)
                ->dateColumn('purchase_date')
                ->between(
                    start: now()->subMonth(12), //@phpstan-ignore-line
                    end: now(),
                )
                ->perMonth()
                ->sum('purchase_price');

        $expenses = Trend::model(VehicleExpense::class)
                ->dateColumn('date')
                ->between(
                    start: now()->subMonth(12), //@phpstan-ignore-line
                    end: now(),
                )
                ->perMonth()
                ->sum('value');

        return [
            'datasets' => [
                [
                    'label'           => __('Purchases'),
                    'data'            => $purchase->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#33ff42',
                    'borderColor'     => '#93ff33',
                ],
                [
                    'label'           => __('Sales'),
                    'data'            => $sale->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor'     => '#9BD0F5',
                ],
                [
                    'label'           => __('Expenses'),
                    'data'            => $expenses->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#fa0808',
                    'borderColor'     => '#f89c7c',
                ],
            ],
            'labels' => $sale->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
