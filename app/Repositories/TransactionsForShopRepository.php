<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\Order;
use App\Models\ShopPayment;
use App\Models\TransactionsForShop;

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
        $count = TransactionsForShop::count();
        return $count;
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
        $shop_payments = ShopPayment::where('shop_id', $shop_id)->sum('amount');

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
        
        $total_amount = $shop_payments + $orders_amount;
        $actual_amount = $total_amount - ($transaction_amount + $collection_amount);

        return $actual_amount;
    }
}