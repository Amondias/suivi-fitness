<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clients',[ClientController::class,'index']);
Route::post('/clients/create',[ClientController::class,'create']);
Route::get('/clients/{id}',[ClientController::class,'show']);
Route::put('/clients/{id}',[ClientController::class,'edit']);
Route::delete('/clients/{id}',[ClientController::class,'delete']);
Route::get('/clients/{id}/subscriptions',[ClientController::class,'subscriptions']);
Route::get('/clients/{id}/payments',[ClientController::class,'payments']);



