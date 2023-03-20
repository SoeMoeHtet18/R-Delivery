<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopUserController;
use App\Http\Controllers\TownshipController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('/users', AdminController::class);
    Route::resource('/riders', RiderController::class);
    Route::resource('/shopusers', ShopUserController::class);
    Route::resource('/townships',TownshipController::class);
    Route::resource('/shops', ShopController::class);
    Route::resource('/orders', OrderController::class);
    Route::resource('/cities', CityController::class);
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
