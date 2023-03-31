<?php

namespace App\Repositories;

use App\Models\TransactionsForShop;

class TransactionsForShopRepository
{
    public function getAllTransactionsForShopQuery()
    {
        $query = TransactionsForShop::leftJoin('shops', 'shops.id','transactions_for_shops.shop_id')->leftJoin('users','users.id','transactions_for_shops.paid_by')->select('transactions_for_shops.*','shops.name as shop_name','users.name as paid_by');
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
}