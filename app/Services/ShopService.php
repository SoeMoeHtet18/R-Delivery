<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Order;
use App\Models\Shop;
use App\Models\ShopPayment;
use App\Models\TransactionsForShop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShopService
{
    public function saveShopData($data)
    {
        $user = auth()->user();
        $shop = new Shop();
        $shop->name = $data['name'];
        $shop->address =  $data['address'];
        $shop->phone_number = $data['phone_number'];
        $shop->branch_id = $user->branch_id;
        $shop->save();
        return $shop;
    }

    public function updateShopByID($data, $shop)
    {
        $shop->name = $data['name'];
        $shop->address =  $data['address'];
        $shop->phone_number = $data['phone_number'];
        $shop->save();
        return $shop;
    }

    public function deleteShopByID($id)
    {
        Shop::destroy($id);
    }

    public function getPayableAmount($shop_id)
    {
        $shop_payments = ShopPayment::where('shop_id', $shop_id)->sum('amount');

        $pay_later_cod_amount = Order::where('shop_id', $shop_id)
            ->where('payment_method', 'cash_on_delivery')
            ->where('pay_later', 1)
            ->where('payment_flag', 1)
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
}