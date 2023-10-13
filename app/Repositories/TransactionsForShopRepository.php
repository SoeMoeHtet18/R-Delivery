<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\CustomerCollection;
use App\Models\Order;
use App\Models\ShopPayment;
use App\Models\TransactionsForShop;
use DateTime;

class TransactionsForShopRepository
{
    public function getAllTransactionsForShopQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = TransactionsForShop::leftJoin('shops', 'shops.id','transactions_for_shops.shop_id')
            ->leftJoin('users','users.id','transactions_for_shops.paid_by')
            ->where('transactions_for_shops.branch_id', $branch_id)
            ->select('transactions_for_shops.*','shops.name as shop_name','users.name as paid_by');
        return $query;
    }

    public function getTransactionsForShopByID($id)
    {
        $transaction_for_shops = TransactionsForShop::with('shop', 'user')->findOrFail($id);
        return $transaction_for_shops;
    }

    public function getTransactionsForShopListByShopID($id)
    {
        $transaction_for_shops = TransactionsForShop::where('shop_id', $id)->get();
        return $transaction_for_shops;
    }

    public function getTransactionsForShopDetailByID($id)
    {
        $transaction_for_shops = TransactionsForShop::where('id', $id)->first();
        return $transaction_for_shops;
    }

    public function getAllTransactionsForShopCount()
    {
        $user = auth()->user();
        return TransactionsForShop::where('branch_id', $user->branch_id)->count();
    }

    public function getTransactionsQueryByShopID($id)
    {
        $query = TransactionsForShop::where('shop_id',$id)->leftJoin('users','users.id','transactions_for_shops.paid_by')->select('transactions_for_shops.*','users.name as paid_by');
        return $query;
    }

    public function getPaidAmountByShopUser($shop_id)
    {
        $paid_credit = TransactionsForShop::where('shop_id',$shop_id)->sum('amount');
        return $paid_credit;
    }

    public function getPaymentHistoryForShop($shop_id)
    {
        $payment_histories = TransactionsForShop::where('shop_id',$shop_id)
            ->orderByDesc('created_at')
            ->select('amount as paid_amount','created_at')
            ->get();
        foreach($payment_histories as $payment_history) {
            $payment_history->type = 'company';
        }
        return $payment_histories;
    }

    public function getActualAmount($order_ids, $shop_id)
    {
        // $shop_payments = ShopPayment::where('shop_id', $shop_id)->sum('amount');

        $paid_orders = Order::where('payment_flag', 1)
                            ->where('shop_id', $shop_id)->get();
        
        $paid_orders_amount = 0;

        foreach ($paid_orders as $order) {
            if ($order->payment_method === 'cash_on_delivery') {
                $paid_orders_amount += $order->total_amount + $order->markup_delivery_fees;
            } elseif (in_array($order->payment_method, ['item_prepaid', 'all_prepaid'])) {
                $paid_orders_amount += $order->markup_delivery_fees;
            }
        }

        $orders = Order::whereIn('id', $order_ids)->get();

        $orders_amount = 0;

        foreach ($orders as $order) {
            if ($order->payment_method === 'cash_on_delivery') {
                $orders_amount += $order->total_amount + $order->markup_delivery_fees;
            } elseif (in_array($order->payment_method, ['item_prepaid', 'all_prepaid'])) {
                $orders_amount += $order->markup_delivery_fees;
            }
        }

        $transaction_amount = TransactionsForShop::where('shop_id', $shop_id)
            ->sum('amount');

        $collection_amount = Collection::where('shop_id', $shop_id)->sum('paid_amount');

        $customerCollectionAmount = CustomerCollection::where('shop_id', $shop_id)->sum('paid_amount');

        $total_amount = $orders_amount + $paid_orders_amount;
        $actual_amount = $total_amount - ($transaction_amount + $collection_amount + $customerCollectionAmount);

        return $actual_amount;
    }

    public function getShopTransactionsByShopID($shop_id, $start, $end)
    {
        if($start && $end) {
            $start = str_replace(' GMT+0630 (Myanmar Time)', '', $start);
            $end = str_replace(' GMT+0630 (Myanmar Time)', '', $end);
            $start = new DateTime($start);
            $end = new DateTime($end);
        }

        return TransactionsForShop::where('shop_id', $shop_id)
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->with('user')->get();
    }
}