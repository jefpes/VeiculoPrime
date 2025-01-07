<?php

namespace App\Livewire\Front;

use App\Models\Vehicle;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProductsPage extends Component
{
    public Collection $vehicles;

    public function mount(): void
    {
        $this->vehicles = Vehicle::whereNull('sold_date') //@phpstan-ignore-line
            ->with(['model.brand', 'photos', 'store'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
