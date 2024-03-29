<?php

use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\BranchApiController;
use App\Http\Controllers\Api\CityApiController;
use App\Http\Controllers\Api\ItemTypeApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\RiderApiController;
use App\Http\Controllers\Api\ShopApiController;
use App\Http\Controllers\Api\ShopUserApiController;
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
Route::get('/shops/{id}', [ShopApiController::class, 'getShopDetail']);
Route::post('/shops/{id}', [ShopApiController::class, 'updateShopDetail']);
Route::get('/shops/{id}/shop-users', [ShopApiController::class, 'getShopUsers']);
Route::get('/shops/{id}/orders', [ShopApiController::class, 'getShopOrders']);
Route::get('/shops/{id}/financial-amounts', [ShopApiController::class, 'getAmountsRelatedToOrder']);
Route::get('/shops/{id}/pick-ups', [ShopApiController::class, 'getShopPickUps']);
Route::get('/shops/{id}/exchanges', [ShopApiController::class, 'getShopExchanges']);
Route::get('/shops/{id}/payments', [ShopApiController::class, 'getShopPayments']);
Route::get('/shops/{id}/transactions', [ShopApiController::class, 'getShopTransactions']);
Route::get('/shops/{id}/download-pdf', [ShopApiController::class, 'downloadShopPdf']);
Route::get('/shop-users', [ShopUserApiController::class, 'getShopUsers']);
Route::post('/shop-users', [ShopUserApiController::class, 'store']);
Route::post('/shop-users/{id}', [ShopUserApiController::class, 'updateShopUser']);
Route::post('/shop-users/{id}/check-password', [ShopUserApiController::class, 'checkPassword']);
Route::get('/users', [AdminApiController::class, 'getUserTableData']);
Route::post('/save-user-data', [AdminApiController::class, 'storeUserData']);
Route::post('/update-user-data', [AdminApiController::class, 'updateUserData']);
Route::get('/cities', [CityApiController::class, 'getCityTableData']);
Route::post('/save-city-data', [CityApiController::class, 'storeCityData']);
Route::post('/update-city-data', [CityApiController::class, 'updateCityData']);
Route::get('/branches', [BranchApiController::class, 'getBranchTableData']);
Route::post('/save-branch-data', [BranchApiController::class, 'storeBranchData']);
Route::get('/branches/{id}', [BranchApiController::class, 'getBranchDetail']);
Route::get('/item-types', [ItemTypeApiController::class, 'getItemTypes']);
Route::post('/item-types', [ItemTypeApiController::class, 'store']);
Route::post('/item-types/{id}', [ItemTypeApiController::class, 'update']);
Route::delete('/item-types/{id}', [ItemTypeApiController::class, 'delete']);
Route::get('/notifications', [AdminApiController::class, 'getNotifications']);
Route::put('/notifications/{id}/read', [AdminApiController::class, 'makeNotificationRead']);
Route::post('/new-notifications', [AdminApiController::class, 'getNewNotifications']);
