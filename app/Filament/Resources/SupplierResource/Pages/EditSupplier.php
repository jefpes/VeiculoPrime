<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplier extends EditRecord
{
    protected static string $resource = SupplierResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['supplier_type'] === 'Jurídica') {
            $data['rg']             = null;
            $data['birth_date']     = null;
            $data['marital_status'] = null;
            $data['gender']         = 'OUTRO';
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
