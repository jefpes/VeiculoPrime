<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    public function getDefaultActiveTab(): string | int | null
    {
        return 'Unsold';
    }

    public function getTabs(): array
    {
        return [
            'All'  => Tab::make(__('All')),
            'Sold' => Tab::make(__('Sold'))->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('sold_date')),
            'Unsold' => Tab::make(__('Unsold'))->modifyQueryUsing(fn (Builder $query) => $query->whereNull('sold_date')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
