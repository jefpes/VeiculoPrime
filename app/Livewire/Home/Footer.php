<?php

namespace App\Livewire\Home;

use App\Models\{Settings};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Footer extends Component
{
    public function render(): View
    {
        return view('livewire.home.footer', ['company' => Settings::with('addresses')->first()]);
    }
}
