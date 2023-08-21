<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\CustomerCollection;
use App\Models\Order;
use App\Models\Shop;
use App\Models\ShopPayment;
use App\Models\ShopUser;
use App\Models\TransactionsForShop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShopRepository
{
    public function getAllShops()
    {
        $branch_id = auth()->user()->branch_id;
        $shops = Shop::where('branch_id', $branch_id)->orderBy('name','asc')->get();
        return $shops;
    }

    public function getAllShopsQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = Shop::select('*')->where('branch_id', $branch_id);
        return $query;
    }

    public function getShopByID($id)
    {
        $shop = Shop::findOrFail($id);
        return $shop;
    }

    public function getShopUsersByShopID($id)
    {
        $query = ShopUser::where('shop_id', $id);
        return $query;
    }

    public function getShopOrdersByShopID($id)
    {
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->where('orders.shop_id', $id)
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 
                'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name', 
                'item_types.name as item_type_name', 'delivery_types.name as delivery_type_name');
        return $query;
    }

    public function getAllShopCount()
    {
        $shopcount = Shop::count();
        return $shopcount;
    }

    public function getPayableAmountForShop($shop_id)
    {
        $shop_payments = ShopPayment::where('shop_id', $shop_id)->sum('amount');

        $payLaterAmount = Order::where('shop_id', $shop_id)
            ->where('payment_method', 'cash_on_delivery')
            ->where('pay_later', 1)
            ->where('status', 'success')
            ->sum(DB::raw('total_amount + markup_delivery_fees'));

        $todayDate = Carbon::today();

        $remainingOrdersAmount = 0;

        $orders = Order::where('shop_id', $shop_id)->get();

        foreach ($orders as $order) {
            $notified_date = $order->delivery_type->notified_on - 1;
            $calculated_date = $order->created_at->addDays($notified_date);
    
            if ($todayDate->eq($calculated_date)) {
                if ($order->payment_method === 'cash_on_delivery' && !$order->pay_later) {
                    $remainingOrdersAmount += $order->total_amount + $order->markup_delivery_fees;
                } elseif (in_array($order->payment_method, ['item_prepaid', 'all_prepaid'])) {
                    $remainingOrdersAmount += $order->markup_delivery_fees;
                }
            }
        }

        $transactionAmount = TransactionsForShop::where('shop_id', $shop_id)->sum('amount');

        $collectionAmount = Collection::where('shop_id', $shop_id)->sum('paid_amount');

        $customerCollectionAmount = CustomerCollection::where('shop_id', $shop_id)->sum('paid_amount');
        
        $totalAmount = $shop_payments + $payLaterAmount + $remainingOrdersAmount;
        return $totalAmount - ($transactionAmount + $collectionAmount + $customerCollectionAmount);
    }

    public function getTotalCreditForShop($shopId)
    {
        $codAmount = Order::where('shop_id', $shopId)
                        ->where('payment_method', 'cash_on_delivery')
                        ->sum(DB::raw('total_amount + markup_delivery_fees'));

        $remainingAmount = Order::where('shop_id', $shopId)
                            ->whereNot('payment_method', 'cash_on_delivery')
                            ->sum('markup_delivery_fees');

        $customerCollectionAmount = CustomerCollection::where('shop_id', $shopId)->sum('paid_amount');
        
        return strval($codAmount + $remainingAmount - $customerCollectionAmount);
    }
}
