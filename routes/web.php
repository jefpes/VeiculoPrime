<?php

use App\Http\Middleware\CheckTenant;
use App\Livewire\{Home};
use Illuminate\Support\Facades\Route;

Route::get('/', Home\Index::class)->middleware(CheckTenant::class);

Route::get('/show/{id}', Home\Show::class)->middleware(CheckTenant::class);
