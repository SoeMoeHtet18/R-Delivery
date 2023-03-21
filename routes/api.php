<?php

<<<<<<< HEAD
use App\Http\Controllers\Api\CityApiController;
=======
use App\Http\Controllers\Api\CustomerPaymentApiController;
>>>>>>> feature/customerpayment-crud
use App\Http\Controllers\Api\RiderApiController;
use App\Http\Controllers\Api\ShopApiController;
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
    Route::post('riders/get-order-list?status=pending', [RiderApiController::class, 'getOrderList']);
    Route::get('riders/{id}/get-shop-list', [RiderApiController::class, 'getShopListByRiderID']);
    Route::post('create-customer-payment-by-rider', [CustomerPaymentApiController::class, 'insertCustomerPayment']);
});

Route::get('get-shop-list', [ShopApiController::class, 'getAllShopList']);
Route::get('cities', [CityApiController::class, 'getAllCityList']);
Route::get('townships', [TownshipApiController::class, 'getAllTownshipList']);
Route::post('townships-get-by-city', [TownshipApiController::class, 'getAllTownshipListByCityID']);