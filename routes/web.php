<?php

use App\Livewire\{Home};
use Illuminate\Support\Facades\Route;

Route::get('/', Home\Index::class)->name('home');

Route::get('/show/{id}', Home\Show::class)->name('show.v');
