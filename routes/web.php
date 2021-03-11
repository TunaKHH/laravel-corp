<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LunchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RestaurantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LunchController::class, 'index']);
Route::get('/record', [LunchController::class, 'record'])->name('record');

Route::resource('lunch', LunchController::class);

Route::resource('user', UserController::class);

Route::resource('task', TaskController::class);

Route::resource('restaurant', RestaurantController::class);
