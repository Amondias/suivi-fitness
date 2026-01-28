<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\PaymentsController ;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\ExerciseCategoriesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {

    // PUBLIC
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    // AUTHENTIFIÉ
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::post('users/{id}/assign-role', [UserController::class, 'assignRole']);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Routes Client
    Route::middleware(['role:client'])->group(function () {
        Route::get('my-subscription', [SubscriptionsController::class, 'mySubscription']);
        Route::get('my-payments', [PaymentsController::class, 'myPayments']);
        Route::get('my-programs', [ProgramsController::class, 'myPrograms']);
        Route::post('programs/{id}/subscribe', [ProgramsController::class, 'subscribe']);
    });

    // Categories - Public endpoints (authentifiés)
    Route::get('categories', [ExerciseCategoriesController::class, 'index']);
    Route::get('categories/{exerciseCategory}', [ExerciseCategoriesController::class, 'show']);

    // Exercices - Public endpoints (authentifiés)
    Route::get('exercises', [ExerciseController::class, 'index']);
    Route::get('exercises/{exercise}', [ExerciseController::class, 'show']);
    Route::get('exercises/category/{categoryId}', [ExerciseController::class, 'showByCategory']);

    // Routes Coach
    Route::middleware(['role:coach,admin'])->group(function () {
        Route::apiResource('programs', ProgramsController::class)->except(['index', 'show']);
        Route::post('categories', [ExerciseCategoriesController::class, 'store']);
        Route::put('categories/{exerciseCategory}', [ExerciseCategoriesController::class, 'update']);
        Route::delete('categories/{exerciseCategory}', [ExerciseCategoriesController::class, 'destroy']);
        Route::post('exercises', [ExerciseController::class, 'store']);
        Route::put('exercises/{exercise}', [ExerciseController::class, 'update']);
        Route::delete('exercises/{exercise}', [ExerciseController::class, 'destroy']);
        Route::post('programs/{id}/exercise',[ProgramsController::class,'addExercise']);
        Route::get('clients', [ClientController::class, 'index']);
        Route::get('clients/{id}/subscriptions', [ClientController::class, 'subscriptions']);
        Route::get('clients/active', [ClientController::class, 'active']);

    });

    // Routes Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('plans', PlanController::class);
        Route::apiResource('subscriptions', SubscriptionsController::class);
        Route::apiResource('payments', PaymentsController::class);
        Route::get('reports/financial', [ReportController::class, 'financialReport']);
        Route::get('subscriptions/expiring', [SubscriptionsController::class, 'expiringSoon']);
        Route::get('clients/{id}/payments', [ClientController::class, 'payments']);
        Route::get('clients/expired', [ClientController::class, 'expired']);


    });
});


