<?php

namespace App\Repositories;

use App\Models\ShopPayment;

class ShopPaymentRepository
{
    public function getAllShopPaymentsByDESC()
    {
        $shop_payments = ShopPayment::leftJoin('shops','shops.id','shop_payments.shop_id')->select('shop_payments.*','shops.name as shop_name');
        return $shop_payments;
    }

    public function getShopPaymentByID($id)
    {
        $shop_payments = ShopPayment::with('shop')->findOrFail($id);
        return $shop_payments;
    }
}