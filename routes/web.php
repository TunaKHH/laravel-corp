<?php

use App\Http\Controllers\LiffController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RestaurantMealController;
use App\Http\Controllers\RestaurantPhotoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LunchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskOrderController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\LoginController;

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



Route::get('/liff/login', [LiffController::class,'login']);
Route::get('/line', [LoginController::class,'pageLine']);
Route::get('/callback/login', [LoginController::class,'lineLoginCallBack']);

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class,'show'])->name('login');
    Route::post('login', [LoginController::class,'authenticate'])->name('login.enter');
    Route::get('register', [RegisterController::class,'show'])->name('register');
    Route::post('register', [RegisterController::class,'create'])->name('register.enter');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class,'logout'])->name('logout');

    Route::get('/', [LunchController::class, 'index'])->name('index');
    Route::get('/record', [LunchController::class, 'record'])->name('record');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.edit');
    Route::resource('user', UserController::class)->except(['edit','update']);
    Route::resource('task', TaskController::class);
    Route::resource('lunch', LunchController::class);
    Route::resource('taskOrder', TaskOrderController::class);
    Route::resource('restaurant', RestaurantController::class);
    Route::resource('restaurantMeal', RestaurantMealController::class);
    Route::resource('restaurantPhoto', RestaurantPhotoController::class);

    Route::post('task/lock', [TaskController::class,'lock'])->name('task.lock');
    Route::post('task/unlock', [TaskController::class,'unlock'])->name('task.unlock');
    Route::post('task/finish/{task}', [TaskController::class,'finish'])->name('task.finish');
//    Route::post('/uploadImage', [RestaurantController::class, 'uploadImage'])->name('uploadImage');
});
