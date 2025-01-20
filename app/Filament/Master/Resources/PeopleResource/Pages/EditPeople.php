<?php

namespace App\Filament\Master\Resources\PeopleResource\Pages;

use App\Filament\Master\Resources\PeopleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeople extends EditRecord
{
    protected static string $resource = PeopleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getSubNavigation(): array
    {
        if (filled($cluster = static::getCluster())) {
            return $this->generateNavigationItems($cluster::getClusteredComponents());
        }

        return [];
    }
}
