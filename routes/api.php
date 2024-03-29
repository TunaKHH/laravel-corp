<?php

use App\Http\Controllers\ECPayController;
use App\Http\Controllers\LineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'line',
], function ($router) {
    Route::post('/webhook', [LineController::class, 'webhook']);
    Route::post('/addOrder', [LineController::class, 'addOrder']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'ecpay',
], function ($router) {
    Route::post('/callback', [ECPayController::class, 'handleECPayCallback'])->name('ecpay.callback');
});
