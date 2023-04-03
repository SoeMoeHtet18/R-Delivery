<?php

namespace App\Services;

use App\Http\Traits\FileUploadTrait;
use App\Models\TransactionsForShop;

class TransactionsForShopService
{
    use FileUploadTrait;
    public function saveTransactionForShopData($data, $file)
    {
        $transactions_for_shops = new TransactionsForShop();
        $transactions_for_shops->shop_id = $data['shop_id'];
        $transactions_for_shops->amount =  $data['amount'];
        if($file) {
            $file_name = $this->uploadFile($file, 'public', 'transactions_for_shop');
            $transactions_for_shops->image = $file_name;
        } else {
            $transactions_for_shops->image = null;
        }
        $transactions_for_shops->type = $data['type'];
        $transactions_for_shops->paid_by = $data['paid_by'];
        $transactions_for_shops->description = $data['description'] ?? null;
        $transactions_for_shops->save();
    }

    public function updateTransactionForShopByID($data, $transaction_for_shop, $file)
    {   
        $transaction_for_shop->shop_id = $data['shop_id'];
        $transaction_for_shop->amount =  $data['amount'];
        if($file) {
            $file_name = $this->uploadFile($file, 'public', 'transactions for shop');
            $transaction_for_shop->image = $file_name;
        } else {
            $transaction_for_shop->image = $transaction_for_shop->image;
        }
        $transaction_for_shop->type = $data['type'];
        $transaction_for_shop->paid_by = $data['paid_by'];
        $transaction_for_shop->description = $data['description'];
        $transaction_for_shop->save();
    }

    public function deleteTransactionForShopByID($id)
    {
        TransactionsForShop::destroy($id);
    }
}