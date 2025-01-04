<?php

namespace App\Livewire\Front;

use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class IndexPage extends Component
{
    public $banners = [];

    public $categories = [];

    public $mostSearched = [];

    public $products = [];

    public function mount()
    {
        $this->banners = $this->getBanners();

        $this->categories = Brand::query()->get();

        $this->mostSearched = $this->getMostSearched();

        $this->products = Vehicle::whereNull('sold_date')
            ->with(['model.brand', 'photos', 'store'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getBanners()
    {
        $banners = Vehicle::query()
            ->where('emphasis', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->with(['photos' => function ($query) {
                $query->where('main', true);
            }])
            ->get(['id']);

        if ($banners->isEmpty()) {
            $banners = Vehicle::query()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->with(['photos' => function ($query) {
                    $query->where('main', true);
                }])
                ->get(['id']);
        }

        return $banners;
    }

    private function getMostSearched()
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
