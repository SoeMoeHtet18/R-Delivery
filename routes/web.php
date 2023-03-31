<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopPaymentController;
use App\Http\Controllers\ShopUserController;
use App\Http\Controllers\TownshipController;
use App\Http\Controllers\TransactionsForShopController;
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
    Route::get('/dashboard', [DashboardController::class, 'count']);
    Route::resource('/users', AdminController::class);
    Route::resource('/riders', RiderController::class);
    Route::get('/riders/get-pending-orders-by-rider-id/{id}', [OrderController::class, 'getPendingOrdersTableByRiderID']);
    Route::get('/riders/get-order-history-by-rider-id/{id}', [OrderController::class, 'getOrderHistoryTableByRiderID']);
    Route::get('/riders/{id}/assign-township', [RiderController::class, 'assignTownship']);
    Route::put('/riders/{id}/assign-township', [RiderController::class, 'assignTownshipToRider']);
    Route::resource('/shopusers', ShopUserController::class);
    Route::resource('/townships',TownshipController::class);
    Route::get('townships/{id}/get-pending-orders-by-township-id', [OrderController::class, 'getPendingOrderTableByTownshipID']);
    Route::get('townships/{id}/get-completed-orders-by-township-id', [OrderController::class, 'getCompletedOrderTableByTownshipID']);
    Route::get('townships/{id}/get-canceled-orders-by-township-id', [OrderController::class, 'getCanceledOrderTableByTownshipID']);
    Route::resource('/shops', ShopController::class);
    Route::get('/shops/get-shop-users-by-shop-id/{id}', [ShopUserController::class, 'getShopUsersTable']);
    Route::get('/shops/get-shop-orders-by-shop-id/{id}', [OrderController::class, 'getShopOrdersTable']);
    Route::resource('/orders', OrderController::class);
    Route::get('/orders/{id}/assign-rider', [OrderController::class, 'assignRider']);
    Route::post('/orders/{id}/assign-rider', [OrderController::class, 'assignRiderToOrder']);
    Route::get('/get-data-by-customer-phone/{phone}', [OrderController::class, 'getDataByCustomerPhoneNumber']);
    Route::resource('/cities', CityController::class);
    Route::get('/cities/{id}/townships', [TownshipController::class, 'getTownshipsTableByCityID']);
    Route::resource('/itemtypes', ItemTypeController::class);
    Route::resource('/shoppayments', ShopPaymentController::class);
    Route::resource('/customer-payments', CustomerPaymentController::class);
    Route::resource('/transactions-for-shop', TransactionsForShopController::class);
    Route::get('/ajax-get-orders-data', [OrderController::class, 'getAjaxOrderData']);
    Route::get('/ajax-get-users-data', [AdminController::class, 'getAjaxUserData']);
    Route::get('/ajax-get-shops-data', [ShopController::class, 'getAjaxShopData']);
    Route::get('/ajax-get-shop-users-data', [ShopUserController::class, 'getAjaxShopUserData']);
    Route::get('/ajax-get-city-data', [CityController::class, 'getAjaxCityData']);
    Route::get('/ajax-get-item-type-data', [ItemTypeController::class, 'getAjaxItemTypeData']);
    Route::get('/ajax-get-riders-data', [RiderController::class, 'getAjaxRiderData']);
    Route::get('/ajax-get-shop-payment-data', [ShopPaymentController::class, 'getAjaxShopPaymentData']);
    Route::get('/ajax-get-customer-payment-data', [CustomerPaymentController::class, 'getAjaxCustomerPaymentData']);
    Route::get('/ajax-get-townships-data', [TownshipController::class, 'getAjaxTownshipData']);
    Route::get('/ajax-get-transactions-data', [TransactionsForShopController::class, 'getAjaxTransactionForShopData']);
    
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
