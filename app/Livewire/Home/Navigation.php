<?php

namespace App\Livewire\Home;

use App\Models\Company;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Navigation extends Component
{
    public function render(): View
    {
        return view(
            'livewire.home.navigation',
            [
                'company'    => Company::query()->find(1),
                'loginRoute' => Filament::getPanel('admin')->getPath() . Filament::getPanel('admin')->getLoginRouteSlug(),
            ]
        );
    }

}
