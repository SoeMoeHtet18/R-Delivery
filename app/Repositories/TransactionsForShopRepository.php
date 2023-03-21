<?php

namespace App\Repositories;

use App\Models\TransactionsForShop;

class TransactionsForShopRepository
{
    public function getAllTransactionsForShopByDESC()
    {
        $transaction_for_shops = TransactionsForShop::leftJoin('shops', 'shops.id','transactions_for_shops.shop_id')->leftJoin('users','users.id','transactions_for_shops.paid_by')->select('transactions_for_shops.*','shops.name as shop_name','users.name as paid_by')->orderBy('id','DESC')->get();
        return $transaction_for_shops;
    }

    public function getTransactionsForShopByID($id)
    {
        $transaction_for_shops = TransactionsForShop::with('shop', 'user')->findOrFail($id);
        return $transaction_for_shops;
    }
}