<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['client_type'] === 'Jurídica') {
            $data['rg']             = null;
            $data['birth_date']     = null;
            $data['marital_status'] = null;
            $data['gender']         = 'OUTRO';
        }

        return $data;
    }
}
