<?php

namespace App\Livewire\Home;

use App\Models\{Vehicle};
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

        $this->vehicles = $this->getVehicles();
    }

    private function getEmphasingVehicles(): Collection
    {
        $emphasingVehicles = Vehicle::query()
            ->where('sold_date', null)
            ->where('emphasis', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->with(['photos']);

        if ($emphasingVehicles->get(['id'])->isEmpty()) {
            $emphasingVehicles = Vehicle::query()
                ->where('sold_date', null)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->with(['photos']);
        }

        if (tenant() == null) {
            $emphasingVehicles = $emphasingVehicles->whereHas('tenant', function ($query) {
                $query->whereHas('setting', function ($query) {
                    $query->where('marketplace', true);
                });
            });
        }

        return $emphasingVehicles->get(['id']);
    }

    private function getVehicles(): Collection
    {
        $v = Vehicle::query()
            ->with(['model.brand', 'photos', 'store'])
            ->where('sold_date', null)
            ->orderBy('created_at', 'desc')
            ->limit(16);

        if (tenant() == null) {
            $v = $v->whereHas('tenant', function ($query) {
                $query->whereHas('setting', function ($query) {
                    $query->where('marketplace', true);
                });
            });
        }

        return $v->get();
    }
}
