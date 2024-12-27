<?php

namespace App\Livewire\Home;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Footer extends Component
{
    public function render(): View
    {
        return view('livewire.home.footer', ['company' => Company::with('addresses')->where('tenant_id', (session()->get('tenant')->id ?? null))->first()]);
    }
}
