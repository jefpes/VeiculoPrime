<?php

use App\Livewire\{Home};
use Illuminate\Support\Facades\Route;

Route::get('/', Home\Index::class);

Route::get('/show/{id}', Home\Show::class);
