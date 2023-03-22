<?php

namespace App\Services;

use App\Models\ShopPayment;

class ShopPaymentService
{
    public function saveShopPaymentData($data)
    {
        $shop = new ShopPayment();
        $shop->shop_id = $data['shop_id'];
        $shop->amount =  $data['amount'];
        $shop->image = $data['image'] ?? null;
        $shop->type = $data['type'];
        $shop->save();
    }

    public function updateShopPaymentByID($data, $shop_payment)
    {   
        $shop_payment->shop_id = $data['shop_id'];
        $shop_payment->amount =  $data['amount'];
        $shop_payment->image = $data['image'] ?? null;
        $shop_payment->type = $data['type'];
        $shop_payment->save();
    }

    public function deleteShopPaymentByID($id)
    {
        ShopPayment::destroy($id);
    }
}