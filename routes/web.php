<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LunchController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantMealController;
use App\Http\Controllers\RestaurantPhotoController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskOrderController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ValidateLogin;
use App\Http\Middleware\ValidateRegistration;
use Illuminate\Support\Facades\Route;

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

Route::get('/google/auth', [SocialiteController::class, 'redirectToGoogleAuthPage'])->name('google.auth');
Route::get('/google/auth/callback', [SocialiteController::class, 'handleGoogleLoginCallback']);

Route::get('/line/auth', [SocialiteController::class, 'redirectToLineAuthPage'])->name('line.auth');
Route::get('/line/auth/callback', [SocialiteController::class, 'handleLineLoginCallback']);

Route::get('/line', [LoginController::class, 'pageLine']);
Route::get('/callback/login', [LoginController::class, 'lineLoginCallBack']);

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate'])->name('login.enter')->middleware(ValidateLogin::class);
    Route::get('register', [RegisterController::class, 'show'])->name('register');
    Route::post('register', [RegisterController::class, 'create'])->name('register.enter')->middleware(ValidateRegistration::class);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [LunchController::class, 'index'])->name('index');
    Route::get('/record', [LunchController::class, 'record'])->name('record');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.edit');
    Route::resource('user', UserController::class)->except(['edit', 'update']);
    Route::resource('task', TaskController::class);
    Route::resource('lunch', LunchController::class);
    Route::resource('taskOrder', TaskOrderController::class);
    Route::resource('restaurant', RestaurantController::class);
    Route::resource('restaurantMeal', RestaurantMealController::class);
    Route::resource('restaurantPhoto', RestaurantPhotoController::class);

    Route::post('task/lock', [TaskController::class, 'lock'])->name('task.lock');
    Route::post('task/unlock', [TaskController::class, 'unlock'])->name('task.unlock');
    Route::put('task/finish/{task}', [TaskController::class, 'prefinish'])->name('task.prefinish');
    Route::post('task/finish/{task}', [TaskController::class, 'finish'])->name('task.finish');

//    Route::post('/uploadImage', [RestaurantController::class, 'uploadImage'])->name('uploadImage');
});
