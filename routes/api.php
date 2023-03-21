<?php

use App\Http\Controllers\Api\CustomerPaymentApiController;
use App\Http\Controllers\Api\RiderApiController;
use App\Http\Controllers\Api\ShopUserApiController;
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

Route::post('shop_user_login', [ShopUserApiController::class, 'shopUsersLoginApi']);
Route::post('create-shop-user', [ShopUserApiController::class, 'create']);
Route::middleware('auth:shop-user-api')->group( function () {
    Route::get('shopuser/{id}', [ShopUserApiController::class, 'show']);
    Route::get('shopowner-order-list/{id}', [ShopUserApiController::class, 'orderListByShopOwnerID']);
    Route::get('shopowner-create-order-list/{id}', [ShopUserApiController::class, 'orderCreateByShopOwner']);
    Route::post('update-shop-user/{id}', [ShopUserApiController::class, 'update']);
});
Route::post('rider-login', [RiderApiController::class, 'riderLoginApi']);
Route::middleware('auth:rider-api')->group(function() {
    Route::get('riders/{id}', [RiderApiController::class, 'show']);
    Route::post('create-customer-payment-by-rider', [CustomerPaymentApiController::class, 'insertCustomerPayment']);
});
