<?php

namespace App\Filament\Master\Resources\VehicleTypeResource\Pages;

use App\Filament\Master\Resources\VehicleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageVehicleTypes extends ManageRecords
{
    protected static string $resource = VehicleTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
