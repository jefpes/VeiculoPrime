<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\{PaymentInstallments, Vehicle};
use Carbon\Carbon;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    public array $dataInstallments = []; //@phpstan-ignore-line

    public int $oldVehicleId;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->oldVehicleId                                              = $data['vehicle_id'];
        $data['discount'] > 0 ? $data['discount_surcharge']              = 'discount' : $data['discount_surcharge'] = 'surcharge';
        $data['number_installments'] > 1 ? $data['payment_type']         = 'on_time' : $data['payment_type'] = 'in_sight';
        $data['payment_type'] === 'on_time' ? $data['installment_value'] = ($data['total'] - ($data['down_payment'] ? $data['down_payment'] : 0)) / $data['number_installments'] : 'in_sight';
        $data['number_installments'] > 1 ? $data['first_installment']    = PaymentInstallments::where('sale_id', $this->record->id)->first()->due_date : $data['first_installment'] = null; //@phpstan-ignore-line

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['vehicle_id'] !== $this->oldVehicleId) {
            Vehicle::where('id', $this->oldVehicleId)->update(['sold_date' => null]); //@phpstan-ignore-line
            Vehicle::where('id', $data['vehicle_id'])->update(['sold_date' => $data['date_sale']]); //@phpstan-ignore-line
        }
        $data['surcharge'] = $data['discount_surcharge'] === 'discount' ? 0 : $data['surcharge'];
        $data['discount']  = $data['discount_surcharge'] === 'surcharge' ? 0 : $data['discount'];

        if ($data['payment_type'] === 'in_sight') {
            $data['number_installments'] = 1;
            $data['status']              = 'PAGO';
            $data['down_payment']        = 0;
            $data['date_payment']        = $data['date_sale'];
            PaymentInstallments::where('sale_id', $this->getRecord()->id)->delete(); //@phpstan-ignore-line
        }

        if ($data['payment_type'] === 'on_time') {
            $data['status']         = 'PENDENTE';
            $this->dataInstallments = array_merge($this->dataInstallments, [
                'installment_value' => $data['installment_value'],
                'first_installment' => Carbon::parse($data['first_installment']),
            ]);
        }

        if ($this->getRecord()->number_installments > 1 && $this->data['number_installments'] == 1) { //@phpstan-ignore-line
            PaymentInstallments::where('sale_id', $this->getRecord()->id)->delete();//@phpstan-ignore-line
        }

        unset($data['installment_value']);
        unset($data['first_installment']);
        unset($data['payment_type']);
        unset($data['discount_surcharge']);

        return $data;
    }

    protected function afterSave(): void
    {
        Vehicle::where('id', $this->record->vehicle_id)->update(['sold_date' => $this->record->date_sale]); //@phpstan-ignore-line

        if ($this->record->number_installments > 1) { //@phpstan-ignore-line
            if (PaymentInstallments::where('sale_id', $this->record->id) !== null) { //@phpstan-ignore-line
                PaymentInstallments::where('sale_id', $this->record->id)->delete(); //@phpstan-ignore-line
            }

            for ($i = 0; $i < $this->record->number_installments; $i++) { //@phpstan-ignore-line
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
