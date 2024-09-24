<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function mutateFormDataBeforeSave(array $data): array //@phpstan-ignore-line
    {
        if ($data['taxpayer_type'] === 'Jurídica') {
            $data['rg']             = null;
            $data['birth_date']     = null;
            $data['marital_status'] = null;
        }

        return $data;
    }
}
