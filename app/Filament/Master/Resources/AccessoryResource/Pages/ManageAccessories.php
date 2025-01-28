<?php

namespace App\Filament\Master\Resources\AccessoryResource\Pages;

use App\Filament\Master\Resources\AccessoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAccessories extends ManageRecords
{
    protected static string $resource = AccessoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
