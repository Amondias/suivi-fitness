<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentsController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::resource('payments', PaymentsController::class);
Route::get('payments/report', [PaymentsController::class, 'report'])->name('payments.report');
