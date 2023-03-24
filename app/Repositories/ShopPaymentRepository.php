<?php

namespace App\Repositories;

use App\Models\ShopPayment;

class ShopPaymentRepository
{
    public function getAllShopPaymentsQuery()
    {
        $query = ShopPayment::leftJoin('shops','shops.id','shop_payments.shop_id')->select('shop_payments.*','shops.name as shop_name');
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
}