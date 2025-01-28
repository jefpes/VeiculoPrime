<?php

namespace App\Filament\Master\Resources\VehicleResource\Pages;

use App\Filament\Master\Resources\VehicleResource;
use App\Models\Vehicle;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
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
            'All'    => Tab::make(__('All'))->badge(Vehicle::query()->count()),
            'Sold'   => Tab::make(__('Sold'))->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('sold_date'))->badge(Vehicle::query()->whereNotNull('sold_date')->count()),
            'Unsold' => Tab::make(__('Unsold'))->modifyQueryUsing(fn (Builder $query) => $query->whereNull('sold_date'))->badge(Vehicle::query()->whereNull('sold_date')->count()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
