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

    public ?Company $tenantCompany;

    public function mount(string $id): void
    {
        $this->vehicle = Vehicle::query()->where('id', $id)->withPublicPhotos()->with(['model'])->first(); //@phpstan-ignore-line

        $this->company = Company::query()->first();

        $this->tenantCompany = Company::query()->where('tenant_id', $this->vehicle->tenant_id)->first();
    }

    #[Layout('components.layouts.home')]
    public function render(): View
    {
        return view('livewire.home.show');
    }
}
