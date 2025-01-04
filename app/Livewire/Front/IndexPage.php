<?php

namespace App\Livewire\Front;

use App\Models\Vehicle;
use App\Models\VehicleModel;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class IndexPage extends Component
{
    public $emphasingVehicles = [];

    public $bestSellers = [];

    public $vehicles = [];

    public function mount()
    {
        $this->emphasingVehicles = $this->getEmphasingVehicles();

        $this->bestSellers = $this->getBestSellers();

        $this->vehicles = Vehicle::whereNull('sold_date')
            ->with(['model.brand', 'photos', 'store'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getEmphasingVehicles()
    {
        $emphasingVehicles = Vehicle::query()
            ->where('emphasis', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->with(['photos' => function ($query) {
                $query->where('main', true);
            }])
            ->get(['id']);

        if ($emphasingVehicles->isEmpty()) {
            $emphasingVehicles = Vehicle::query()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->with(['photos' => function ($query) {
                    $query->where('main', true);
                }])
                ->get(['id']);
        }

        return $emphasingVehicles;
    }

    private function getBestSellers()
    {
        return VehicleModel::query()
//            ->withCount(['vehicles' => function ($query) {
//                $query->whereNotNull('sold_date');
//            }])
//            ->orderBy('vehicles_count', 'desc')
            ->with('brand:id,name')
            ->limit(5)
            ->get();
    }
}
