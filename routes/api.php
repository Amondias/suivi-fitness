<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\PaymentsController ;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\ReportController;



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
Route::get('/clients/active',[ClientController::class,'active']);
Route::get('/clients/expired',[ClientController::class,'expired']);


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
        Route::post('subscriptions/{subscription}/pay', [PaymentsController::class, 'processPayment']); 
        Route::post('programs/{id}/subscribe', [ProgramsController::class, 'subscribe']);
    });

    // Routes Coach
    Route::middleware(['role:coach,admin'])->group(function () {
        Route::apiResource('programs', ProgramsController::class)->except(['index', 'show']);
        Route::apiResource('exercises', ExerciseController::class)->except(['index', 'show']);
        Route::get('clients', [ClientController::class, 'index']);
    });

    // Routes Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('subscription-plans', SubscriptionPlansController::class);
        Route::apiResource('subscriptions', SubscriptionsController::class);
        Route::apiResource('payments', PaymentsController::class); 
        Route::post('payments/{payment}/process', [PaymentsController::class, 'processPayment']); 
        Route::post('payments/{payment}/refund', [PaymentsController::class, 'refund']); 
        Route::get('reports/financial', [ReportController::class, 'financialReport']); 
        Route::get('reports/payment-stats', [ReportController::class, 'paymentStats']); 
        Route::get('reports/subscription-analytics', [ReportController::class, 'subscriptionAnalytics']); 
        Route::get('subscriptions/expiring', [SubscriptionsController::class, 'expiringSoon']);
    });
});

