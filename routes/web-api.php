<?php

use App\Http\Controllers\Api\CityApiController;
use App\Http\Controllers\Api\RiderApiController;
use App\Http\Controllers\Api\ShopApiController;
use App\Http\Controllers\Api\TownshipApiController;
use Illuminate\Support\Facades\Route;

Route::get('/get-shop-list', [ShopApiController::class, 'getAllShopList']);
Route::get('/get-city-list', [CityApiController::class, 'getAllCityList']);
Route::get('/get-township-list', [TownshipApiController::class, 'getAllTownshipList']);
Route::get('/get-rider-list', [RiderApiController::class, 'getAllRidersByTownshipID']);
Route::get('/get-delivery-fees-by-township-id', [TownshipApiController::class, 'getDeliveryFees']);