<?php

namespace App\Filament\Admin\Resources\VehicleExpenseCategoryResource\Pages;

use App\Filament\Admin\Resources\VehicleExpenseCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageVehicleExpenseCategories extends ManageRecords
{
    protected static string $resource = VehicleExpenseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
