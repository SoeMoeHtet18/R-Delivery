<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\CustomerCollection;
use App\Models\Order;
use App\Models\ShopPayment;
use App\Models\TransactionsForShop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportCalculationRepository
{
    public function getPayableAmountForShop($shopId)
    {
        $deliveredOrderAmount = Order::where('shop_id', $shopId)
            ->where('status', 'success')
            ->where('payment_method', 'cash_on_delivery')
            ->where(function ($query) {
                $query->where('payment_channel', '!=', 'shop_online_payment')
                    ->orWhereNull('payment_channel');
            })
            ->sum(DB::raw('total_amount + markup_delivery_fees'));

        $deliveredOrderAmount += Order::where('shop_id', $shopId)
            ->where('status', 'success')
            ->where('payment_method', 'item_prepaid')
            ->where(function ($query) {
                $query->where('payment_channel', '!=', 'shop_online_payment')
                    ->orWhereNull('payment_channel');
            })
            ->sum('markup_delivery_fees');

        $deliveredOrderAmount -= Order::where('shop_id', $shopId)
            ->where('status', 'success')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('payment_method', ['cash_on_delivery', 'item_prepaid'])
                        ->where('payment_channel', 'shop_online_payment');
                })->orWhere('payment_method', 'all_prepaid');
            })
            ->sum(DB::raw('delivery_fees + extra_charges - COALESCE(discount, 0)'));

        $todayDate = Carbon::today();

        $remainingOrdersAmount = 0;

        $orders = Order::where('shop_id', $shopId)
            ->whereNotIn('status', ['success', 'cancel', 'cancel_request'])
            ->get();

        foreach ($orders as $order) {
            $notifiedDate = $order->delivery_type->notified_on - 1;
            $calculatedDate = $order->created_at->addDays($notifiedDate);

            if ($todayDate->greaterThanOrEqualTo($calculatedDate)) {
                if (
                    $order->payment_method === 'cash_on_delivery' &&
                    $order->payment_channel === 'shop_online_payment' ||
                    $order->payment_method === 'item_prepaid' &&
                    $order->payment_channel === 'shop_online_payment' ||
                    $order->payment_method === 'all_prepaid'
                ) {
                    $remainingOrdersAmount -= ($order->delivery_fees + $order->extra_charges - $order->discount);
                } elseif ($order->payment_method === 'cash_on_delivery' && !$order->pay_later) {
                    $remainingOrdersAmount += $order->total_amount + $order->markup_delivery_fees;
                } elseif ($order->payment_method === 'item_prepaid') {
                    $remainingOrdersAmount += $order->markup_delivery_fees;
                }
            }
        }

        $customerCollectionAmount = CustomerCollection::where('shop_id', $shopId)
            ->whereDate('schedule_date', $todayDate)
            ->sum('paid_amount');

        $collectionAmountPaidByCompany = Collection::where('shop_id', $shopId)->sum('paid_amount');

        $transactionAmountPaidByCompany = TransactionsForShop::where('shop_id',$shopId)->sum('amount');

        $paymentFromShop = ShopPayment::where('shop_id',$shopId)->sum('amount');

        $totalCredit = ($deliveredOrderAmount ?? 0) + ($remainingOrdersAmount ?? 0) + ($paymentFromShop ?? 0)
            - ($customerCollectionAmount ?? 0);
        
        $totalReceived = ($collectionAmountPaidByCompany ?? 0) + ($transactionAmountPaidByCompany ?? 0);
        
        return $totalCredit - $totalReceived;
    }

    public function getTotalCreditForShop($shopId)
    {
        // Check whether key 'total_credit' exists in cache
        if(Cache::has('total_credit')) {
            // If the key exists in cache, return the cached value
            return Cache::get('total_credit');
        } else {
            // If the key does not exist in cache, calculate the value from the database

            // Calculate the total amount of COD (cash on delivery) orders that the shop will receive
            $codAmount = Order::where('shop_id', $shopId)
                ->where('payment_method', 'cash_on_delivery')
                ->where(function ($query) {
                    $query->where('payment_channel', '!=', 'shop_online_payment')
                        ->orWhereNull('payment_channel');
                })
                ->sum(DB::raw('total_amount + markup_delivery_fees'));

            // Retrieve the remaining amounts of remaining orders (item_prepaid)
            $remainingAmount = Order::where('shop_id', $shopId)
                ->where('payment_method', 'item_prepaid')
                ->sum('markup_delivery_fees');

            // Calculate the subtracted amount based on specific payment conditions
            $subtractedAmount = Order::where('shop_id', $shopId)
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->whereIn('payment_method', ['cash_on_delivery', 'item_prepaid'])
                            ->where('payment_channel', 'shop_online_payment');
                    })->orWhere('payment_method', 'all_prepaid');
                })
                ->sum(DB::raw('delivery_fees + extra_charges - COALESCE(discount, 0)'));

            // Calculate the total amount collected from customer collections
            $customerCollectionAmount = CustomerCollection::where('shop_id', $shopId)->sum('paid_amount');

            // Calculate the total amount received as shop payments
            $paymentFromShop = ShopPayment::where('shop_id',$shopId)->sum('amount');

            // Calculate the total credit for the shop by subtracting relevant amounts
            $totalCredit = strval(($codAmount + $remainingAmount + $paymentFromShop)
                - ($customerCollectionAmount + $subtractedAmount));

            // Cache the calculated total credit for 30 seconds
            Cache::put('total_credit', $totalCredit, $seconds = 30);
            
            // Return the calculated total credit
            return $totalCredit;
        }
    }

    public function getPayableAndReceivableAmountsForShopByDate($shopId, $start, $end, $type)
    {
        $query = Order::where('shop_id', $shopId)
            ->where('payment_flag', 0)
            ->whereBetween('created_at', [$start, $end]);

        if ($type == 'payable') {
            $query->whereIn('payment_method', ['cash_on_delivery', 'item_prepaid'])
                ->selectRaw('DATE(created_at) as date, SUM(CASE WHEN payment_method = "cash_on_delivery"
                    AND payment_channel != "shop_online_payment" OR payment_channel IS null
                    THEN total_amount + COALESCE(markup_delivery_fees, 0)
                    ELSE COALESCE(markup_delivery_fees, 0) END) as total_amount');
        } else {
            $query->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('payment_method', ['cash_on_delivery', 'item_prepaid'])
                        ->where('payment_channel', 'shop_online_payment');
                })->orWhere('payment_method', 'all_prepaid');
            })
                ->selectRaw('DATE(created_at) as date,
                    SUM(delivery_fees + COALESCE(extra_charges, 0) - COALESCE(discount, 0)) as total_amount');
        }

        return $query->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }
}
