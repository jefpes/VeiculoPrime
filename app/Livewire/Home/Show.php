<?php

namespace App\Livewire\Home;

use App\Models\{Company, Vehicle};
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Vehicle $vehicle;

    public Company $company;

    public function mount(int $id): void
    {
        $this->vehicle = Vehicle::with('photos', 'model')->findOrFail($id);
        $this->company = Company::query()->first();
    }

    #[Layout('components.layouts.home')]
    public function render(): View
    {
        return view('livewire.home.show');
    }
}
