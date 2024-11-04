<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\{PaymentInstallments, Vehicle};
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    public array $dataInstallments = []; //@phpstan-ignore-line

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['surcharge'] = $data['discount_surcharge'] === 'discount' ? 0 : $data['surcharge'];
        $data['discount']  = $data['discount_surcharge'] === 'surcharge' ? 0 : $data['discount'];

        if ($data['payment_type'] === 'in_sight') {
            $data['number_installments'] = 1;
            $data['status']              = 'PAGO';
            $data['down_payment']        = 0;
            $data['date_payment']        = $data['date_sale'];
        }

        if ($data['payment_type'] === 'on_time') {
            $data['status']         = 'PENDENTE';
            $this->dataInstallments = array_merge($this->dataInstallments, [
                'installment_value' => $data['installment_value'],
                'first_installment' => Carbon::parse($data['first_installment']),
            ]);
        }

        unset($data['installment_value']);
        unset($data['first_installment']);
        unset($data['payment_type']);
        unset($data['discount_surcharge']);

        return $data;
    }

    protected function afterCreate(): void
    {
        Vehicle::where('id', $this->record->vehicle_id)->update(['sold_date' => $this->record->date_sale]); //@phpstan-ignore-line

        if ($this->record->number_installments > 1) { //@phpstan-ignore-line
            if (PaymentInstallments::where('sale_id', $this->record->id) !== null) { //@phpstan-ignore-line
                PaymentInstallments::where('sale_id', $this->record->id)->delete(); //@phpstan-ignore-line
            }

            for ($i = 0; $i < $this->record->number_installments; $i++) {
                if ($i > 0) {
                    $this->dataInstallments['first_installment'] = $this->dataInstallments['first_installment']->addMonthNoOverflow(1);
                }

                $installmentData = [
                    'sale_id'  => $this->record->id, //@phpstan-ignore-line
                    'user_id'  => $this->record->user_id, //@phpstan-ignore-line
                    'value'    => $this->dataInstallments['installment_value'],
                    'due_date' => $this->dataInstallments['first_installment'],
                    'status'   => 'PENDENTE',
                ];

                PaymentInstallments::create($installmentData); //@phpstan-ignore-line
            }
        }
    }
}
