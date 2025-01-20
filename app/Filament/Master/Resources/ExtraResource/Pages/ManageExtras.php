<?php

namespace App\Filament\Master\Resources\ExtraResource\Pages;

use App\Filament\Master\Resources\ExtraResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExtras extends ManageRecords
{
    protected static string $resource = ExtraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
