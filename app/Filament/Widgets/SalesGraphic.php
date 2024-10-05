<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SalesGraphic extends ChartWidget
{
    protected static ?string $heading = 'Sales in the last year';

    protected function getData(): array
    {
        // Obtém a data de hoje
        $now = Carbon::now();

        // Obtém as vendas dos últimos 12 meses e agrupa por mês
        $salesByMonth = Sale::selectRaw('MONTH(date_sale) as month, COUNT(*) as total') //@phpstan-ignore-line
            ->whereBetween('date_sale', [$now->subYear()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Inicializa os dados dos meses com 0
        $data = array_fill(1, 12, 0);

        // Substitui os valores do array $data pelos valores reais das vendas de cada mês
        foreach ($salesByMonth as $month => $sale) {
            $data[$month] = $sale->total;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Sales',
                    'data'            => array_values($data), // Usa os valores das vendas
                    'backgroundColor' => '#36A2EB',
                    'borderColor'     => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
