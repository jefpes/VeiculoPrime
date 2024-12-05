<?php

namespace App\Filament\Master\Resources\TenantResource\Pages;

use App\Filament\Master\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
