<?php

namespace App\Filament\Resources\VehicleExpenseResource\Pages;

use App\Filament\Resources\VehicleExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageVehicleExpenses extends ManageRecords
{
    protected static string $resource = VehicleExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
