<?php

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

class VehicleCluster extends Cluster
{
    protected static ?string $navigationIcon = 'icon-car';

    protected static ?int $navigationSort = 20;

    public static function getNavigationLabel(): string
    {
        return __('Vehicles');
    }

    public function getTitle(): string
    {
        return __('Vehicle');
    }

    public static function getModelLabel(): string
    {
        return __('Vehicle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Vehicles');
    }

    public static function getLabel(): string
    {
        return __('Vehicle');
    }
}
