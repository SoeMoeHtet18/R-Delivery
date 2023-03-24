<?php

use App\Http\Controllers\Api\CityApiController;
use App\Http\Controllers\Api\CustomerPaymentApiController;
use App\Http\Controllers\Api\RiderApiController;
use App\Http\Controllers\Api\ShopApiController;
use App\Http\Controllers\Api\ShopPaymentApiController;
use App\Http\Controllers\Api\ShopUserApiController;
use App\Http\Controllers\Api\TownshipApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('create-shop', [ShopApiController::class, 'create']);
Route::post('shop_user_login', [ShopUserApiController::class, 'shopUsersLoginApi']);
Route::post('create-shop-user', [ShopUserApiController::class, 'create']);
Route::middleware('auth:shop-user-api')->group( function () {
    Route::get('shopuser/{id}', [ShopUserApiController::class, 'show']);
    Route::get('shopowner-order-list/{id}', [ShopUserApiController::class, 'orderListByShopOwnerID']);
    Route::get('shopowner-create-order-list/{id}', [ShopUserApiController::class, 'orderCreateByShopOwner']);
    Route::post('update-shop-user/{id}', [ShopUserApiController::class, 'update']);
    Route::post('create-shop-payment-by-shop-owner', [ShopPaymentApiController::class, 'insertShopPayment']);
    Route::post('update-shop/{id}', [ShopApiController::class, 'update']);
    Route::get('get-shop-info/{id}', [ShopApiController::class, 'getShopDetailInfo']);
    Route::post('shop_users/{id}/change-order-status',[ShopUserApiController::class, 'changeOrderStatus']);
    Route::get('shop_users/{id}/get-shop_payment-list', [ShopPaymentApiController::class, 'getShopPaymentListByShopID']);
    Route::post('shop_users/{id}/get-shop_payment-detail', [ShopPaymentApiController::class, 'getShopPaymentDetailByID']);
});
Route::post('rider-login', [RiderApiController::class, 'riderLoginApi']);
Route::post('create-rider', [RiderApiController::class, 'create']);
Route::middleware('auth:rider-api')->group(function() {
    Route::get('riders/{id}', [RiderApiController::class, 'show']);
    Route::post('riders/get-order-list?status=pending', [RiderApiController::class, 'getOrderList']);
    Route::get('riders/{id}/get-shop-list', [RiderApiController::class, 'getShopListByRiderID']);
    Route::post('update-rider/{id}', [RiderApiController::class, 'update']);
    Route::post('create-customer-payment-by-rider', [CustomerPaymentApiController::class, 'insertCustomerPayment']);
    Route::post('riders/{id}/change-order-status',[RiderApiController::class, 'changeOrderStatus']);
});

Route::get('get-shop-list', [ShopApiController::class, 'getAllShopList']);
Route::get('cities', [CityApiController::class, 'getAllCityList']);
Route::get('townships', [TownshipApiController::class, 'getAllTownshipList']);
Route::post('townships-get-by-city', [TownshipApiController::class, 'getAllTownshipListByCityID']);