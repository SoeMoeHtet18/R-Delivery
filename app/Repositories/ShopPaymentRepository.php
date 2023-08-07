<?php

namespace App\Repositories;

use App\Models\ShopPayment;

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

    public function getShopPaymentListByShopID($id)
    {
        $shop_payments = ShopPayment::where('shop_id', $id)->get();
        return $shop_payments;
    }

    public function getShopPaymentDetailByID($id)
    {
        $shop_payment = ShopPayment::where('id', $id)->first();
        return $shop_payment;
    }

    public function getAllShopPaymentCount()
    {
        $shop_payment_count = ShopPayment::count();
        return $shop_payment_count;
    }

    public function getShopPaymentQueryByShopID($id)
    {
        $shop_payments = ShopPayment::where('shop_id', $id);
        return $shop_payments;
    }
}