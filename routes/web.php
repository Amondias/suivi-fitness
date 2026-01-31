<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentsController;

Route::get('/', function () {
    return view('index');
})->name('home');

