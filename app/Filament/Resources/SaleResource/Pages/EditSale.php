<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\PaymentInstallments;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;

use function Filament\Support\is_app_url;

use Throwable;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    public array $dataInstallments = []; //@phpstan-ignore-line

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['discount'] >= 0 ? $data['discount_surcharge']             = 'discount' : $data['discount_surcharge'] = 'surcharge';
        $data['number_installments'] > 1 ? $data['payment_type']         = 'on_time' : $data['payment_type'] = 'in_sight';
        $data['payment_type'] === 'on_time' ? $data['installment_value'] = ($data['total'] - ($data['down_payment'] ? $data['down_payment'] : 0)) / $data['number_installments'] : 'in_sight';

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['surcharge'] = $data['discount_surcharge'] === 'discount' ? 0 : $data['surcharge'];
        $data['discount']  = $data['discount_surcharge'] === 'surcharge' ? 0 : $data['discount'];

        $this->dataInstallments = array_merge($this->dataInstallments, [
            'installment_value' => $data['installment_value'],
            'first_installment' => Carbon::parse($data['first_installment']),
        ]);

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
        if ($this->getRecord()->number_installments > 1) { //@phpstan-ignore-line
            if (PaymentInstallments::where('sale_id', $this->getRecord()->id) !== null) { //@phpstan-ignore-line
                PaymentInstallments::where('sale_id', $this->getRecord()->id)->delete(); //@phpstan-ignore-line
            }

            for ($i = 0; $i < $this->getRecord()->number_installments; $i++) {
                if ($i > 0) {
                    $this->dataInstallments['first_installment'] = $this->dataInstallments['first_installment']->addMonthNoOverflow(1);
                }

                $installmentData = [
                    'sale_id'  => $this->getRecord()->id, //@phpstan-ignore-line
                    'user_id'  => $this->getRecord()->user_id, //@phpstan-ignore-line
                    'value'    => $this->dataInstallments['installment_value'],
                    'due_date' => $this->dataInstallments['first_installment'],
                    'status'   => 'PENDENTE',
                ];

                PaymentInstallments::create($installmentData); //@phpstan-ignore-line
            }
        }
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState(afterValidate: function () {
                $this->callHook('afterValidate');

                $this->callHook('beforeSave');
            });

            $data = $this->mutateFormDataBeforeSave($data);

            $this->handleRecordUpdate($this->getRecord(), $data);

            $this->callHook('afterSave');
            // dd(PaymentInstallments::where('sale_id', $this->getRecord()->id)->get() !== null);

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->rememberData();

        if ($shouldSendSavedNotification) {
            $this->getSavedNotification()?->send();
        }

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }
}
