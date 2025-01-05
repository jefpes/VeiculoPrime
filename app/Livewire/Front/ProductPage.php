<?php

namespace App\Livewire\Front;

use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProductPage extends Component
{
    public Vehicle $vehicle;

    public mixed $paymentMethods = [
        [
            'name' => 'Method 1',
            'icon' => 'https://placehold.co/100x100?text=Method+1+Icon',
        ],
        [
            'name' => 'Method 2',
            'icon' => 'https://placehold.co/100x100?text=Method+2+Icon',
        ],
        [
            'name' => 'Method 3',
            'icon' => 'https://placehold.co/100x100?text=Method+3+Icon',
        ],
        [
            'name' => 'Method 4',
            'icon' => 'https://placehold.co/100x100?text=Method+4+Icon',
        ],
        [
            'name' => 'Method 5',
            'icon' => 'https://placehold.co/100x100?text=Method+5+Icon',
        ],
        [
            'name' => 'Method 6',
            'icon' => 'https://placehold.co/100x100?text=Method+6+Icon',
        ],
        [
            'name' => 'Method 7',
            'icon' => 'https://placehold.co/100x100?text=Method+7+Icon',
        ],
        [
            'name' => 'Method 8',
            'icon' => 'https://placehold.co/100x100?text=Method+8+Icon',
        ],
    ];

    public mixed $similarVehicles;

    public function mount()
    {
        $this->similarVehicles = Vehicle::whereNull('sold_date')
            ->with(['model.brand', 'photos', 'store'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

    }
}
