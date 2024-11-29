<?php

namespace App\Livewire\Home;

use App\Models\{Brand, Company, Vehicle, VehicleType};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\{Computed, Layout, Url};
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;

    public bool $modal = false;

    /** @var array<int> */
    #[Url(except: '', as: 'brands', history: true)]
    public ?array $selectedBrands = [];

    #[Url(except: '', as: 'y-i', history: true)]
    public ?string $year_ini = '';

    #[Url(except: '', as: 'y-e', history: true)]
    public ?string $year_end = '';

    #[Url(except: '', history: true)]
    public ?string $order = 'desc';

    #[Url(except: '', history: true)]
    public ?string $max_price = null;

    #[Url(except: '', as: 'type', history: true)]
    public ?int $vehicle_type_id = null;

    #[Layout('components.layouts.home')]
    public function render(): View
    {
        return view('livewire.home.index', ['company' => Company::query()->find(1)]);
    }

    #[Computed()]
    public function types(): Collection
    {
        return VehicleType::query()->orderBy('name')->get();
    }

    #[Computed()]
    public function years(): Collection
    {
        return Vehicle::query()->where('sold_date', null)
            ->when($this->selectedBrands, fn ($query) => $query->whereHas('model', fn ($query) => $query->whereIn('brand_id', $this->selectedBrands)))
            ->when($this->vehicle_type_id, fn ($query) => $query->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $this->vehicle_type_id)))
            ->when($this->max_price, fn ($query) => $query->where('sale_price', '<=', $this->max_price))
            ->select('year_one')
            ->distinct()
            ->orderBy('year_one')
            ->get();
    }

    #[Computed()]
    public function vehicles(): LengthAwarePaginator
    {
        return Vehicle::with('model', 'photos')
            ->whereNull('sold_date')
            ->when($this->selectedBrands, fn ($query) => $query->whereHas('model', fn ($query) => $query->whereIn('brand_id', $this->selectedBrands)))
            ->when($this->year_ini, fn ($query) => $query->where('year_one', '>=', $this->year_ini))
            ->when($this->year_end, fn ($query) => $query->where('year_one', '<=', $this->year_end))
            ->when($this->order, fn ($query) => $query->orderBy('sale_price', $this->order))
            ->when($this->max_price, fn ($query) => $query->where('sale_price', '<=', $this->max_price))
            ->when($this->vehicle_type_id, fn ($query) => $query->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $this->vehicle_type_id)))
            ->paginate();
    }

    #[Computed()]
    public function prices(): Collection
    {
        return Vehicle::query()->where('sold_date', null)
            ->when($this->selectedBrands, fn ($query) => $query->whereHas('model', fn ($query) => $query->whereIn('brand_id', $this->selectedBrands)))
            ->when($this->year_ini, fn ($query) => $query->where('year_one', '>=', $this->year_ini))
            ->when($this->year_end, fn ($query) => $query->where('year_one', '<=', $this->year_end))
            ->when($this->vehicle_type_id, fn ($query) => $query->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $this->vehicle_type_id)))
            ->select('sale_price')
            ->distinct()
            ->orderBy('sale_price')
            ->get();
    }

    #[Computed()]
    public function brands(): Collection
    {
        return Brand::query()->join('vehicle_models', 'brands.id', '=', 'vehicle_models.brand_id')
            ->join('vehicles', 'vehicle_models.id', '=', 'vehicles.vehicle_model_id')
            ->whereNull('vehicles.sold_date')
            ->when($this->vehicle_type_id, fn ($query) => $query->where('vehicle_models.vehicle_type_id', $this->vehicle_type_id))
            ->select('brands.*')
            ->distinct()
            ->orderBy('brands.name')
            ->get();
    }

    public function updatedVehicleTypeId(): void
    {
        $this->reset(['selectedBrands', 'order', 'max_price', 'year_ini', 'year_end']);
    }

    public function updatedSelectedBrands(): void
    {
        $this->reset(['order', 'max_price', 'year_ini', 'year_end']);
        $this->resetPage();
    }

    public function updatedYearIni(): void
    {
        $this->reset(['order', 'max_price']);
        $this->resetPage();
    }

    public function updatedYearEnd(): void
    {
        $this->reset(['order', 'max_price']);
        $this->resetPage();
    }

    public function updatedOrder(): void
    {
        $this->resetPage();
    }

    public function updatedMaxPrice(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'vehicle_type_id',
            'selectedBrands',
            'order',
            'max_price',
            'year_ini',
            'year_end',
        ]);
    }
}
