<?php

namespace App\Repositories;

use App\Models\TransactionsForShop;

class TransactionsForShopRepository
{
    public function getAllTransactionsForShopByDESC()
    {
        $transaction_for_shops = TransactionsForShop::leftJoin('shops', 'shops.id','transactions_for_shops.shop_id')->select('transactions_for_shops.*','shops.name as shop_name')->orderBy('id','DESC')->get();
        return $transaction_for_shops;
    }
}