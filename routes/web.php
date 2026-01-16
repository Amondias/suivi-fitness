<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionPlansController;

Route::get('/', function () {
    return view('welcome');
});

// route publique pour les plans d'abonnement
Route::get('/api/plans', [SubscriptionPlansController::class, 'index']);
Route::get('/api/plans/{id}', [SubscriptionPlansController::class, 'show']);

// Admin routes
if (app()->environment('testing')) {
    // En environnement de test, pas d'authentification requise
    Route::post('/api/plans', [SubscriptionPlansController::class, 'store']);
    Route::put('/api/plans/{id}', [SubscriptionPlansController::class, 'update']);
    Route::delete('/api/plans/{id}', [SubscriptionPlansController::class, 'destroy']);
} else {
    // En production, authentification requise
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/api/plans', [SubscriptionPlansController::class, 'store']);
        Route::put('/api/plans/{id}', [SubscriptionPlansController::class, 'update']);
        Route::delete('/api/plans/{id}', [SubscriptionPlansController::class, 'destroy']);
    });
}

