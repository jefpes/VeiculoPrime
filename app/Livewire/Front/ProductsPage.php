<?php

namespace App\Livewire\Front;

use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProductsPage extends Component
{
    public $vehicles = [];

    public function mount()
    {
        $this->vehicles = Vehicle::whereNull('sold_date')
            ->with(['model.brand', 'photos', 'store'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
