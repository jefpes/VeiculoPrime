<?php

namespace App\Livewire\Home;

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
        $this->banners = Photo::query()->where('public', true)
            ->whereHasMorph('photoable', Store::class)
            ->get();

        $this->about = Settings::query()->first()->about;
    }
}
