<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('orders', OrderController::class)->except(['update']);
Route::prefix('/orders')->group(function () {
    Route::get('/repair-status/{status}', [OrderController::class, 'getOrdersWithRepairStatus']);

    Route::prefix('/{order}/state')->group(function () {
        Route::put('/set-price', [OrderController::class, 'setPrice']);
        Route::put('/pay', [OrderController::class, 'pay']);
        Route::put('/payment-accepted', [OrderController::class, 'paymentAccepted']);
        Route::put('/finalize-order', [OrderController::class, 'finalizeOrder']);
        Route::put('/cancel-order', [OrderController::class, 'cancelOrder']);
        Route::get('/available-functions', [OrderController::class, 'availableFunctions']);
    });
});
