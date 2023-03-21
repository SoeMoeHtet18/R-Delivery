<?php

namespace App\Services;

use App\Models\TransactionsForShop;

class TransactionsForShopService
{
    public function saveTransactionForShopData($data)
    {
        $transactions_for_shops = new TransactionsForShop();
        $transactions_for_shops->shop_id = $data['shop_id'];
        $transactions_for_shops->amount =  $data['amount'];
        $transactions_for_shops->image = $data['image'] ?? null;
        $transactions_for_shops->type = $data['type'];
        $transactions_for_shops->paid_by = $data['paid_by'];
        $transactions_for_shops->save();
    }

    public function updateTransactionForShopByID($data, $transaction_for_shop)
    {   
        $transaction_for_shop->shop_id = $data['shop_id'];
        $transaction_for_shop->amount =  $data['amount'];
        $transaction_for_shop->image = $data['image'] ?? null;
        $transaction_for_shop->type = $data['type'];
        $transaction_for_shop->paid_by = $data['paid_by'];
        $transaction_for_shop->save();
    }

    public function deleteTransactionForShopByID($id)
    {
        TransactionsForShop::destroy($id);
    }
}