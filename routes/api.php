<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => ['api']
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/products', [ProductController::class, 'list']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/orders', [OrderController::class, 'list']);
    
    Route::middleware('role:merchant')->group(function () {
        Route::post('/products', [ProductController::class, 'create']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    });

    Route::middleware('role:customer')->group(function () {
        Route::post('/checkout', [OrderController::class, 'checkout']);
    });
});
