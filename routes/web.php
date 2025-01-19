<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
])->group(function () {
    Route::get('/', \App\Livewire\Home\IndexPage::class)->name('index');
    Route::get('/about', \App\Livewire\Home\AboutPage::class)->name('about');
    Route::get('/vehicles', \App\Livewire\Home\ProductsPage::class)->name('vehicles');
    Route::get('/vehicle/{vehicle}', \App\Livewire\Home\ProductPage::class)->name('vehicle');
});
