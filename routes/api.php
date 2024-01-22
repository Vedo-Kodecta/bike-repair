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
    //Route::get('/get-user-orders', [OrderController::class, 'customerOrders']);

    Route::middleware('auth:sanctum')->prefix('/{order}/state')->group(function () {
        Route::put('/set-price', [OrderController::class, 'setPrice'])->middleware(['checkUserRole:2']);
        Route::put('/pay', [OrderController::class, 'pay'])->middleware(['checkUserRole:1']);;
        Route::put('/payment-accepted', [OrderController::class, 'paymentAccepted'])->middleware(['checkUserRole:2']);;
        Route::put('/finalize-order', [OrderController::class, 'finalizeOrder'])->middleware(['checkUserRole:2']);;
        Route::put('/cancel-order', [OrderController::class, 'cancelOrder'])->middleware(['checkUserRole:1']);;
        Route::get('/available-functions', [OrderController::class, 'availableFunctions']);
    });
});
