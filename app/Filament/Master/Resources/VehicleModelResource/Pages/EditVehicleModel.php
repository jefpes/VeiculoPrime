<?php

namespace App\Filament\Master\Resources\VehicleModelResource\Pages;

use App\Filament\Master\Resources\VehicleModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleModel extends EditRecord
{
    protected static string $resource = VehicleModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
