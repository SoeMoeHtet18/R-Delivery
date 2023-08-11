<?php

namespace App\Services;

use App\Http\Traits\FileUploadTrait;
use App\Models\Order;
use App\Models\TransactionsForShop;

class TransactionsForShopService
{
    use FileUploadTrait;
    public function saveTransactionForShopData($data, $file)
    {
        $user = auth()->user();

        $transactions_for_shops = new TransactionsForShop();
        $transactions_for_shops->shop_id = $data['shop_id'];
        $transactions_for_shops->amount =  $data['amount'];
        if ($file) {
            $file_name = $this->uploadFile($file, 'public', 'transactions for shop');
            $transactions_for_shops->image = $file_name;
        } else {
            $transactions_for_shops->image = null;
        }
        $transactions_for_shops->type = $data['type'];
        $transactions_for_shops->paid_by = $data['paid_by'];
        $transactions_for_shops->description = $data['description'] ?? null;
        $transactions_for_shops->branch_id = $user->branch_id;
        $transactions_for_shops->save();
    }

    public function updateTransactionForShopByID($data, $transaction_for_shop, $file)
    {
        $transaction_for_shop->shop_id = $data['shop_id'];
        $transaction_for_shop->amount =  $data['amount'];
        if ($file) {
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

    // public function getActualAmount($order_ids, $shop_id)
    // {
    //     $raw_total_amount = Order::whereIn('id', $order_ids)
    //         // ->where('payment_flag', 0)
    //         // ->where('payment_method', 'cash_on_delivery')
    //         ->selectRaw('SUM(total_amount + markup_delivery_fees) AS total_amount')
    //         ->first();
    //     $total_amount = $raw_total_amount->total_amount;

    //     $existing_amount = TransactionsForShop::where('shop_id', $shop_id)
    //         ->where('type', 'loan_payment')
    //         ->sum('amount');
    //     $actual_amount = $total_amount - $existing_amount;
    //     return $actual_amount;
    // }

    public function updateDataIfOrderIdsExist($data)
    {
        $orderIds = $data['order_ids'];

        if ($data['type'] === 'fully_payment') {
            $shopId = $data['shop_id'];

            TransactionsForShop::where('shop_id', $shopId)
                ->where('type', 'loan_payment')
                ->update(['type' => 'fully_payment']);

            Order::whereIn('id', $orderIds)
                ->update(['payment_flag' => 1]);
        }
    }
}
