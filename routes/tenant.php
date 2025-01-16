<?php

declare(strict_types = 1);

use App\Http\Middleware\CheckTenant;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\{InitializeTenancyByDomain, PreventAccessFromCentralDomains};

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    CheckTenant::class,
])->group(function () {
    Route::get('/', \App\Livewire\Front\IndexPage::class)->name('index');
    Route::get('/about', \App\Livewire\Front\AboutPage::class)->name('about');
    Route::get('/vehicles', \App\Livewire\Front\ProductsPage::class)->name('vehicles');
    Route::get('/vehicle/{vehicle}', \App\Livewire\Front\ProductPage::class)->name('vehicle');
});
