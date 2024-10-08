<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\{Trend, TrendValue};
use Illuminate\Contracts\Support\Htmlable;

class SalesGraphic extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '30s';

    public function getHeading(): string | Htmlable | null
    {
        return __('Sales in the last year');
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
        return '70vh';
    }

    protected function getData(): array
    {

        $data = Trend::model(Sale::class)
                ->dateColumn('date_sale')
                ->between(
                    start: now()->subMonth(12), //@phpstan-ignore-line
                    end: now(),
                )
                ->perMonth()
                ->sum('total');

        return [
            'datasets' => [
                [
                    'label'           => __('Sales'),
                    'data'            => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor'     => '#9BD0F5',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
