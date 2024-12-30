<?php

namespace App\Providers;

use App\Models\{People, Settings, Vehicle};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vehicle::observe(\App\Observers\VehicleObserver::class);
        People::observe(\App\Observers\PeopleObserver::class);
        Settings::observe(\App\Observers\SettingsObserver::class);
    }
}
