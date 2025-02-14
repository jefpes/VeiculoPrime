<?php

namespace App\Livewire\Home;

use App\Models\{Brand, Vehicle, VehicleType};
use Illuminate\Support\Collection;
use Livewire\Attributes\{Computed, Url};
use Livewire\Component;

class ProductsPage extends Component
{
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

    #[Url(except: '', as: 'type_name', history: true)]
    public ?string $vehicle_type = null;

    #[Computed()]
    public function types(): Collection
    {
        return VehicleType::query()->select('name')->distinct()->get();
    }

    public function getTypes(string $name): Collection
    {
        return VehicleType::query()->where('name', $name)->get()->pluck('id');
    }

    #[Computed()]
    public function vehicles(): Collection
    {
        $v = Vehicle::with('model.type', 'model.brand', 'photos', 'store');

        if (tenant() === null) {
            $v->vehicleMarketPlace(); // @phpstan-ignore-line
        }

        return $v->whereNull('sold_date')
            ->when($this->selectedBrands, fn ($query) => $query->whereHas('model.brand', fn ($query) => $query->whereIn('brand_id', $this->getBrands($this->selectedBrands))))
            ->when($this->year_ini, fn ($query) => $query->where('year_one', '>=', $this->year_ini))
            ->when($this->year_end, fn ($query) => $query->where('year_one', '<=', $this->year_end))
            ->when($this->order, fn ($query) => $query->orderBy('sale_price', $this->order))
            ->when($this->max_price, fn ($query) => $query->where('sale_price', '<=', $this->max_price))
            ->when(
                $this->vehicle_type,
                fn ($query) => $query->whereHas('model', fn ($query) => $query->whereIn('vehicle_type_id', $this->getTypes($this->vehicle_type)))
            )
            ->get();
    }

    #[Computed()]
    public function years(): Collection
    {
        $v = Vehicle::with('model.type');

        if (tenant() === null) {
            $v->vehicleMarketPlace(); // @phpstan-ignore-line
        }

        return $v->whereNull('sold_date')
            ->when($this->selectedBrands, fn ($query) => $query->whereHas('model.brand', fn ($query) => $query->whereIn('brand_id', $this->getBrands($this->selectedBrands))))
            ->when(
                $this->vehicle_type,
                fn ($query) => $query->whereHas('model', fn ($query) => $query->whereIn('vehicle_type_id', $this->getTypes($this->vehicle_type)))
            )
            ->when($this->max_price, fn ($query) => $query->where('sale_price', '<=', $this->max_price))
            ->select('year_one')
            ->distinct()
            ->orderBy('year_one')
            ->get();
    }

    #[Computed()]
    public function prices(): Collection
    {
        $v = Vehicle::with('model.type');

        if (tenant() === null) {
            $v->vehicleMarketPlace(); // @phpstan-ignore-line
        }

        return $v->whereNull('sold_date')
            ->when($this->selectedBrands, fn ($query) => $query->whereHas('model', fn ($query) => $query->whereIn('brand_id', $this->getBrands($this->selectedBrands))))
            ->when($this->year_ini, fn ($query) => $query->where('year_one', '>=', $this->year_ini))
            ->when($this->year_end, fn ($query) => $query->where('year_one', '<=', $this->year_end))
            ->when(
                $this->vehicle_type,
                fn ($query) => $query->whereHas('model', fn ($query) => $query->whereIn('vehicle_type_id', $this->getTypes($this->vehicle_type)))
            )
            ->select('sale_price')
            ->distinct()
            ->orderBy('sale_price')
            ->get();
    }

    #[Computed()]
    public function brands(): Collection
    {
        return Brand::query()
            ->whereHas('models', function ($query) {
                $query->whereHas('vehicles', function ($query) {
                    $query->vehicleMarketPlace()->whereNull('vehicles.sold_date'); // @phpstan-ignore-line
                });

                if ($this->vehicle_type) {
                    $query->whereIn('vehicle_models.vehicle_type_id', $this->getTypes($this->vehicle_type));
                }
            })
            ->orderBy('name')
            ->select('name')
            ->distinct()
            ->get();
    }

    /**
     * @param array<int> $name
     */
    public function getBrands(array $name): Collection
    {
        return Brand::query()->whereIn('name', $name)->get()->pluck('id');
    }

    public function updatedVehicleType(): void
    {
        $this->reset(['selectedBrands', 'order', 'max_price', 'year_ini', 'year_end']);
    }

    public function updatedSelectedBrands(): void
    {
        $this->reset(['order', 'max_price', 'year_ini', 'year_end']);
    }

    public function updatedYearIni(): void
    {
        $this->reset(['order', 'max_price']);
    }

    public function updatedYearEnd(): void
    {
        $this->reset(['order', 'max_price']);
    }

    public function clearFilters(): void
    {
        $this->reset([
            'vehicle_type',
            'selectedBrands',
            'order',
            'max_price',
            'year_ini',
            'year_end',
        ]);
    }
}
