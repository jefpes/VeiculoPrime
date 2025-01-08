<?php

namespace App\Livewire\Front;

use App\Models\{Photo, Settings, Store};
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AboutPage extends Component
{
    public Collection $banners;

    public string $about;

    public function mount(): void
    {
        $this->banners = Photo::where('public', true) //@phpstan-ignore-line
            ->where('photoable_id', Store::firstWhere('active', true)->id) //@phpstan-ignore-line
            ->get();

        $this->about = Settings::first()->about; //@phpstan-ignore-line
    }
}
