<?php

namespace App\Livewire\Home;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Navigation extends Component
{
    public function render(): View
    {
        return view('livewire.home.navigation', ['company' => Company::query()->find(1)]);
    }

}
