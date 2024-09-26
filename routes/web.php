<?php

use Illuminate\Support\Facades\Route;

// Vue Routes
Route::get('{any?}', function () {
    return view('app');
})->where('any', '.*');
