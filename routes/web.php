<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentsController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::resource('payments', PaymentsController::class);
Route::get('payments/report', [PaymentsController::class, 'report'])->name('payments.report');

// Ajout d'une route login pour corriger l'erreur "Route [login] not defined"
Route::get('/login', function () {
    return response()->json(['message' => 'Login page placeholder']);
})->name('login');
