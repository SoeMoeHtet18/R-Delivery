<?php

namespace App\Repositories;

use App\Models\ShopPayment;
use DateTime;

class ShopPaymentRepository
{
    public function getAllShopPaymentsQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = ShopPayment::leftJoin('shops','shops.id','shop_payments.shop_id')->where('shop_payments.branch_id', $branch_id)->select('shop_payments.*','shops.name as shop_name');
        return $query;
    }

    public function getShopPaymentByID($id)
    {
        $shop_payments = ShopPayment::with('shop')->findOrFail($id);
        return $shop_payments;
    }

    public function getShopPaymentListByShopID($id, $start, $end)
    {
        if($start && $end) {
            $start = str_replace(' GMT+0630 (Myanmar Time)', '', $start);
            $end = str_replace(' GMT+0630 (Myanmar Time)', '', $end);
            $start = new DateTime($start);
            $end = new DateTime($end);
        }

        return ShopPayment::where('shop_id', $id)
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->get();
    }

    public function getShopPaymentDetailByID($id)
    {
        $shop_payment = ShopPayment::where('id', $id)->first();
        return $shop_payment;
    }

    public function getAllShopPaymentCount()
    {
        $user = auth()->user();
        return ShopPayment::where('branch_id', $user->branch_id)->count();
    }

    public function getShopPaymentQueryByShopID($id)
    {
        $shop_payments = ShopPayment::where('shop_id', $id);
        return $shop_payments;
    }
}