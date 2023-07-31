<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CollectionGroupController;
use App\Http\Controllers\CustomerCollectionController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryTypeController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderImportController;
use App\Http\Controllers\PaymentNotificationListController;
use App\Http\Controllers\PaymentTypeController;
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
    Route::get('/dashboard', [DashboardController::class, 'count'])->name('dashboard');
    Route::resource('/users', AdminController::class);
    Route::resource('/riders', RiderController::class);
    Route::get('/riders/get-pending-orders-by-rider-id/{id}', [OrderController::class, 'getPendingOrdersTableByRiderID']);
    Route::get('/riders/get-order-history-by-rider-id/{id}', [OrderController::class, 'getOrderHistoryTableByRiderID']);
    Route::get('/riders/get-collection-by-rider-id/{id}', [CollectionController::class, 'getCollectionByRiderID']);
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
    Route::get('/shops/{id}/get-shop-payment-by-shop-id', [ShopPaymentController::class, 'getShopPaymentTableByShopID']);
    Route::get('/shops/{id}/get-transactions-for-shop-by-shop-id', [TransactionsForShopController::class, 'getTransactionsTableByShopID']);
    Route::get('/shops/{id}/get-collections-for-shop-by-shop-id', [CollectionController::class, 'getCollectionsTableByShopID']);
    Route::resource('/orders', OrderController::class);
    Route::get('/order-create-by-shop-id', [OrderController::class, 'createByShopID']);
    Route::get('/orders/{id}/assign-rider', [OrderController::class, 'assignRider']);
    Route::post('/orders/{id}/assign-rider', [OrderController::class, 'assignRiderToOrder']);
    Route::resource('/cities', CityController::class);
    Route::get('/cities/{id}/townships', [TownshipController::class, 'getTownshipsTableByCityID']);
    Route::resource('/itemtypes', ItemTypeController::class);
    Route::resource('/shoppayments', ShopPaymentController::class);
    Route::get('/shoppayment-create-by-shop-id', [ShopPaymentController::class, 'createShopPaymentByShopID']);
    Route::resource('/customer-payments', CustomerPaymentController::class);
    Route::resource('/transactions-for-shop', TransactionsForShopController::class);
    Route::get('/transactions-for-shop-create-by-shop-id', [TransactionsForShopController::class, 'createTransactionForShopByShopID']);
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
    Route::get('/ajax-get-payment-type-data', [PaymentTypeController::class, 'getAjaxPaymentTypeData']);
    Route::get('/ajax-warehouse-data', [OrderController::class, 'getWarehouseData']);
    Route::get('/create-transaction-for-shop-for-selected-orders', [TransactionsForShopController::class, "createTransactionForOrdersByShop"]);
    Route::resource('/payment-types', PaymentTypeController::class);
    Route::get('/import-orders', [OrderImportController::class, 'index']);
    Route::post('/import-orders', [OrderImportController::class, 'upload']);
    Route::get('/ajax-get-cancel-request-orders-data', [OrderController::class, 'getAjaxCancelRequestOrderData']);
    Route::get('/ajax-get-cancel-orders-data', [OrderController::class, 'getAjaxCancelOrderData']);
    Route::get('/ajax-get-warehouse-orders-data', [OrderController::class, 'getAjaxWarehouseOrderData']);
    Route::post('/orders/{id}/change-status', [OrderController::class, 'changeStatus']);
    Route::get('/generate-pdf', [OrderController::class, 'generatePDF']);
    Route::resource('/collection-groups', CollectionGroupController::class);
    Route::get('/ajax-get-collection-groups', [CollectionGroupController::class, 'getAjaxCollectionGroupsdata']);
    Route::resource('/collections', CollectionController::class);
    Route::get('/ajax-get-collection-data', [CollectionController::class, 'getAjaxCollections']);
    Route::get('/ajax-get-collection-groups-data', [CollectionGroupController::class, 'getAjaxCollectionGroups']);
    Route::get('/ajax-get-collections-data-for-shops', [CollectionController::class, 'getAjaxCollectionsForShops']);
    Route::get('/get-collection-list-by-rider-id', [CollectionController::class, 'getCollectionListByRiderId']);
    Route::resource('/delivery-types', DeliveryTypeController::class);
    Route::get('/ajax-get-delivery-types', [DeliveryTypeController::class, 'getDeliveryTypes']);
    Route::get('/payment-channel-confirm/{order_id}', [OrderController::class, 'confirmPaymentChannel']);
    Route::get('/remaining-amount-confirm/{order_id}', [OrderController::class, 'confirmRemainingAmount']);
    Route::get('/remaining-amount-cancel/{order_id}', [OrderController::class, 'cancelRemainingAmount']);
    Route::get('/get-notifications', [AdminController::class, 'getNotification']);
    Route::get('/get-new-notifications', [AdminController::class, 'getNewNotification']);
    Route::get('/payment-notifications', [PaymentNotificationListController::class, 'index']);
    Route::get('/ajax-get-unpaid-order-list', [OrderController::class, 'getAjaxUnpaidOrderList']);
    Route::resource('/customer-collections', CustomerCollectionController::class);
    Route::get('/ajax-get-customer-collections-data', [CustomerCollectionController::class, 'getAjaxCustomerCollections']);
    Route::post('/bulk-discount-update', [OrderController::class, 'bulkDiscountUpdate']);
    Route::get('/get-collections-by-shop', [ShopController::class, 'getCollectionByShop']);
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tracking', [OrderController::class, 'showTracking']);