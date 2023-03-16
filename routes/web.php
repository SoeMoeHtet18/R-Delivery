<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RidersController;
use App\Http\Controllers\ShopUsersController;
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
    return view('welcome');
});

Route::resource('/user', AdminController::class);
Route::resource('/riders', RidersController::class);