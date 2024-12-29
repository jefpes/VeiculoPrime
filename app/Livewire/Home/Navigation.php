<?php

namespace App\Livewire\Home;

use App\Models\{Settings};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Navigation extends Component
{
    public function render(): View
    {
        return view('livewire.home.navigation', ['company' => Settings::query()->first()]);
    }
}
