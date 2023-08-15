<?php

namespace App\Repositories;

use App\Models\Collection;
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
        $shops = Shop::where('branch_id', $branch_id)->get();
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

    public function getPayableAmountForShop($shop_id, $callable_type = 'web')
    {
        $shop_payments = ShopPayment::where('shop_id', $shop_id)->sum('amount');

        $pay_later_cod_amount = Order::where('shop_id', $shop_id)
            ->where('payment_method', 'cash_on_delivery')
            ->where('pay_later', 1)
            ->where('status', 'success')
            ->sum(DB::raw('total_amount + markup_delivery_fees'));

        $todayDate = Carbon::today();

        $cod_orders_amount = 0;
        $remaining_orders_amount = 0;

        $orders = Order::where('shop_id', $shop_id)->get();

        foreach ($orders as $order) {
            $notified_date = $order->delivery_type->notified_on - 1;
            $calculated_date = $order->created_at->addDays($notified_date);
    
            if ($todayDate->eq($calculated_date)) {
                if ($order->payment_method === 'cash_on_delivery' && !$order->pay_later) {
                    $cod_orders_amount += $order->total_amount + $order->markup_delivery_fees;
                } elseif (in_array($order->payment_method, ['item_prepaid', 'all_prepaid'])) {
                    $remaining_orders_amount += $order->markup_delivery_fees;
                }
            }
        }

        $transaction_amount = TransactionsForShop::where('shop_id', $shop_id)
            ->sum('amount');

        $collection_amount = Collection::where('shop_id', $shop_id)->sum('paid_amount');
        
        $total_amount = $shop_payments + $pay_later_cod_amount + $cod_orders_amount + $remaining_orders_amount;
        $actual_amount = $total_amount - ($transaction_amount + $collection_amount);

        return $actual_amount;
    }

    public function getTotalCreditForShop($shop_id)
    {
        $shop_payments = ShopPayment::where('shop_id', $shop_id)->sum('amount');

        $paid_orders = Order::where('payment_flag',1)->get();
        
        $paid_orders_amount = 0;

        foreach ($paid_orders as $order) {
            if ($order->payment_method === 'cash_on_delivery') {
                $paid_orders_amount += $order->total_amount + $order->markup_delivery_fees;
            } elseif (in_array($order->payment_method, ['item_prepaid', 'all_prepaid'])) {
                $paid_orders_amount += $order->markup_delivery_fees;
            }
        }

        $cod_orders_amount = 0;
        $remaining_orders_amount = 0;

        $orders = Order::where('shop_id', $shop_id)
                    ->where('status', 'success')
                    ->where('payment_flag', 0)
                    ->get();

        foreach ($orders as $order) {
            if ($order->payment_method === 'cash_on_delivery') {
                $cod_orders_amount += $order->total_amount + $order->markup_delivery_fees;
            } elseif (in_array($order->payment_method, ['item_prepaid', 'all_prepaid'])) {
                $remaining_orders_amount += $order->markup_delivery_fees;
            }
        }

        $transaction_amount = TransactionsForShop::where('shop_id', $shop_id)
            ->sum('amount');

        $collection_amount = Collection::where('shop_id', $shop_id)->sum('paid_amount');
        
        $total_amount = $shop_payments + $paid_orders_amount + $cod_orders_amount + $remaining_orders_amount;
        $actual_amount = $total_amount - ($transaction_amount + $collection_amount);

        return $actual_amount;
    }
}
