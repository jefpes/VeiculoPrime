<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;

use function Filament\Support\is_app_url;

use Throwable;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

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
        $data['payment_type'] === 'on_time' ? $data['installment_value'] = $data['total'] / $data['number_installments'] : 'in_sight';

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['surcharge'] = $data['discount_surcharge'] === 'discount' ? 0 : $data['surcharge'];
        $data['discount']  = $data['discount_surcharge'] === 'surcharge' ? 0 : $data['discount'];

        $downPayment        = $data['down_payment'] ?? 0;
        $numberInstallments = $data['number_installments'];
        $installmentValue   = $numberInstallments > 1 ? ($data['total'] - $downPayment) / $data['number_installments'] : null;

        // dd($data);

        unset($data['installment_value']);
        unset($data['first_installment']);
        unset($data['payment_type']);
        unset($data['discount_surcharge']);

        return $data;
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

            // dd($data);

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
