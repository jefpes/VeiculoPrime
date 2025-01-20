<?php

namespace App\Filament\Master\Resources\SaleResource\Pages;

use App\Filament\Master\Resources\SaleResource;
use App\Models\Sale;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('New Sale')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'All';
    }

    public function getTabs(): array
    {
        return [
            'All'  => Tab::make(__('All')),
            'Late' => Tab::make(__('Late'))->modifyQueryUsing(fn (Builder $query) => $query->whereHas('paymentInstallments', function ($query) {
                // Verificar se a parcela está vencida (atrasada)
                $query->where('due_date', '<', now())
                      ->whereNull('payment_date'); // Se não houver data de pagamento, está em atraso
            }))->badge(Sale::whereHas('paymentInstallments', function ($query) { //@phpstan-ignore-line
                // Verificar se a parcela está vencida (atrasada)
                $query->where('due_date', '<', now())
                      ->whereNull('payment_date'); // Se não houver data de pagamento, está em atraso
            })
            ->count()),
        ];
    }
}
