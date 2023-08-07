<?php

namespace App\Repositories;

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
}