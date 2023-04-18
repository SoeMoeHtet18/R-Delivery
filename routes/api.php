<?php

use App\Http\Controllers\Api\CityApiController;
use App\Http\Controllers\Api\CustomerPaymentApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\PaymentTypeApiController;
use App\Http\Controllers\Api\RiderApiController;
use App\Http\Controllers\Api\ShopApiController;
use App\Http\Controllers\Api\ShopPaymentApiController;
use App\Http\Controllers\Api\ShopUserApiController;
use App\Http\Controllers\Api\TownshipApiController;
use App\Http\Controllers\Api\TransactionForShopApiController;
use App\Models\TransactionsForShop;
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
Route::post('shop-user/create', [ShopUserApiController::class, 'create']);
Route::middleware('auth:shop-user-api')->group( function () {
    Route::get('shop-user', [ShopUserApiController::class, 'show']);
    Route::get('shop-user/get-order-list', [ShopUserApiController::class, 'orderListByShopOwnerID']);
    Route::post('shop-user/create-order-list', [ShopUserApiController::class, 'orderCreateByShopOwner']);
    Route::post('shop-user/update', [ShopUserApiController::class, 'update']);
    Route::post('shop-user/create-shop-payment', [ShopPaymentApiController::class, 'insertShopPayment']);
    Route::post('update-shop', [ShopApiController::class, 'update']);
    Route::get('get-shop-info', [ShopApiController::class, 'getShopDetailInfo']);
    Route::post('shop-user/delete', [ShopUserApiController::class,'delete']);
    Route::post('delete-shop',[ShopApiController::class, 'delete']);
    Route::post('shop_user/change-order-status',[ShopUserApiController::class, 'changeOrderStatus']);
    Route::get('shop_user/get-shop_payment-list', [ShopPaymentApiController::class, 'getShopPaymentListByShopID']);
    Route::get('get-shop_payment-detail/{id}', [ShopPaymentApiController::class, 'getShopPaymentDetailByID']);
    Route::get('transactions-for-shop/{id}/get-transactions-for-shop-list', [TransactionForShopApiController::class, 'getTransactionForShopListByShopID']);
    Route::get('get-transactions-for-shop-detail/{id}', [TransactionForShopApiController::class, 'getTransactionForShopDetailByID']);
});
Route::post('rider-login', [RiderApiController::class, 'riderLoginApi']);
Route::post('rider/create', [RiderApiController::class, 'create']);
Route::middleware('auth:rider-api')->group(function() {
    Route::get('rider', [RiderApiController::class, 'show']);
    Route::post('rider/get-order-list', [RiderApiController::class, 'getOrderList']);
    Route::get('rider/get-shop-list', [RiderApiController::class, 'getShopListByRiderID']);
    Route::post('rider', [RiderApiController::class, 'update']);
    Route::post('create-customer-payment-by-rider', [CustomerPaymentApiController::class, 'insertCustomerPayment']);
    Route::post('rider/change-order-status',[RiderApiController::class, 'changeOrderStatus']);
    Route::get('customer-payment/{id}', [CustomerPaymentApiController::class, 'customerPaymentDetail']);
    Route::get('get-customer-payment-list', [CustomerPaymentApiController::class, 'getCustomerPaymentList']);
});

Route::get('get-shop-list', [ShopApiController::class, 'getAllShopList']);
Route::get('cities', [CityApiController::class, 'getAllCityList']);
Route::get('townships', [TownshipApiController::class, 'getAllTownshipList']);
Route::post('townships-get-by-city', [TownshipApiController::class, 'getAllTownshipListByCityID']);
Route::post('riders-get-by-township', [RiderApiController::class, 'getAllRidersByTownshipID']);
Route::post('/get-data-by-customer-phone', [OrderApiController::class, 'getDataByCustomerPhoneNumber']);
Route::get('get-payment-type-list', [PaymentTypeApiController::class, 'getAllPaymentType']);