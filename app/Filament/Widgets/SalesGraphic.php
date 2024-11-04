<?php

namespace App\Filament\Widgets;

use App\Models\{Sale, Vehicle, VehicleExpense};
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\{Trend, TrendValue};
use Illuminate\Contracts\Support\Htmlable;

class SalesGraphic extends ChartWidget
{
    protected static ?int $sort = 5;

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
                start: now()->subMonth(12)->startOfMonth(), //@phpstan-ignore-line
                end: now()->endOfMonth()                    // Fim do mês atual
            )
            ->perMonth()
            ->sum('total');

        $purchase = Trend::model(Vehicle::class)
            ->dateColumn('purchase_date')
            ->between(
                start: now()->subMonth(12)->startOfMonth(), //@phpstan-ignore-line
                end: now()->endOfMonth()                    // Fim do mês atual
            )
            ->perMonth()
            ->sum('purchase_price');

        $expenses = VehicleExpense::whereBetween('date', [ //@phpstan-ignore-line
            now()->subMonth(12)->startOfMonth(), //@phpstan-ignore-line
            now()->endOfMonth(),
        ])
                        ->orderBy('date')
                        ->get()->groupBy(function ($date) {
                            return Carbon::parse($date->date)->format('Y/m'); // Agrupa por ano e mês
                        })->map(function ($month) {
                            return $month->sum('value'); // Soma os valores do mês
                        });

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
                    'data'            => $expenses->map(fn ($value) => $value),
                    'backgroundColor' => '#fa0808',
                    'borderColor'     => '#f89c7c',
                ],
            ],
            'labels' => $sale->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('Y/m')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
