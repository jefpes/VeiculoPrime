<?php

namespace App\Livewire\Home;

use App\Models\{Vehicle, VehicleModel};
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class IndexPage extends Component
{
    public Collection $emphasingVehicles;

    public Collection $bestSellers;

    public Collection $vehicles;

    public function mount(): void
    {
        $this->emphasingVehicles = $this->getEmphasingVehicles();

        $this->bestSellers = $this->getBestSellers();

        $this->vehicles = Vehicle::query()
            ->where('sold_date', null)
            ->with(['model.brand', 'photos', 'store'])
            ->orderBy('created_at', 'desc')
            ->limit(16)
            ->get();
    }

    private function getEmphasingVehicles(): Collection
    {
        $emphasingVehicles = Vehicle::query()
            ->where('sold_date', null)
            ->where('emphasis', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->with(['photos'])
            ->get(['id']);

        if ($emphasingVehicles->isEmpty()) {
            $emphasingVehicles = Vehicle::query()
                ->where('sold_date', null)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->with(['photos'])
                ->get(['id']);
        }

        return $emphasingVehicles;
    }

    private function getBestSellers(): Collection
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
