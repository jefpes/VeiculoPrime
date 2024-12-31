<?php

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

class FinancialCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 30;

    public static function getNavigationLabel(): string
    {
        return __('Financial');
    }

    public function getTitle(): string
    {
        return __('Financial');
    }

    public static function getModelLabel(): string
    {
        return __('Financial');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Financial');
    }

    public static function getLabel(): string
    {
        return __('Financial');
    }
}
