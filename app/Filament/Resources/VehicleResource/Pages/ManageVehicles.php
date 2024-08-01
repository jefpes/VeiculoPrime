<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageVehicles extends ManageRecords
{
    public function getDefaultActiveTab(): string | int | null
    {
        return 'Unsold';
    }

    public function getTabs(): array
    {
        return [
            'All'  => Tab::make(__('All')),
            'Sold' => Tab::make(__('Sold'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('sold_date')),
            'Unsold' => Tab::make(__('Unsold'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('sold_date')),
        ];
    }

    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
