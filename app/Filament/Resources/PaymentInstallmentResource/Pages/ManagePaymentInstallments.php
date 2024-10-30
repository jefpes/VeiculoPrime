<?php

namespace App\Filament\Resources\PaymentInstallmentResource\Pages;

use App\Filament\Resources\PaymentInstallmentResource;
use App\Models\PaymentInstallment;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManagePaymentInstallments extends ManageRecords
{
    protected static string $resource = PaymentInstallmentResource::class;

    public function getDefaultActiveTab(): string | int | null
    {
        return 'All';
    }

    public function getTabs(): array
    {
        return [
            'All'  => Tab::make(__('All')),
            'Late' => Tab::make(__('Late'))->modifyQueryUsing(fn (Builder $query) => $query->where('due_date', '<', now())->whereNull('payment_date'))
                ->badge(
                    PaymentInstallment::where('due_date', '<', now()) //@phpstan-ignore-line
                        ->whereNull('payment_date')
                        ->count()
                ),
        ];
    }
}
