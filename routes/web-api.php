<?php

use App\Http\Controllers\Api\CityApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\RiderApiController;
use App\Http\Controllers\Api\ShopApiController;
use App\Http\Controllers\Api\TownshipApiController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/get-shop-list', [ShopApiController::class, 'getAllShopList']);
Route::get('/get-city-list', [CityApiController::class, 'getAllCityList']);
Route::get('/get-township-list', [TownshipApiController::class, 'getAllTownshipListByCityID']);
Route::get('/get-rider-list', [RiderApiController::class, 'getAllRidersByTownshipID']);
Route::get('/get-delivery-fees-by-township-id', [TownshipApiController::class, 'getDeliveryFees']);
Route::post('/save-bulk-order', [OrderApiController::class, 'saveBulkOrder']);
Route::post('/orders', [OrderApiController::class, 'saveOrder']);
Route::post('/orders/{id}', [OrderApiController::class, 'updateOrder']);
Route::post('/setting/update', [SettingController::class, 'updateSetting']);
Route::get('/shops', [ShopApiController::class, 'getShopTableData']);
Route::post('/shops', [ShopApiController::class, 'store']);