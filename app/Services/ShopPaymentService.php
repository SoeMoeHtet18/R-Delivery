<?php

namespace App\Services;

use App\Http\Traits\FileUploadTrait;
use App\Models\ShopPayment;

class ShopPaymentService
{
    use FileUploadTrait;

    public function saveShopPaymentData($data, $file)
    {
        $shop_payment = new ShopPayment();
        $shop_payment->shop_id = $data['shop_id'];
        $shop_payment->amount =  $data['amount'];
        if($file) {
            $file_name = $this->uploadFile($file, 'public', 'shop payment');
            $shop_payment->image = $file_name;
        } else {
            $shop_payment->image = null;
        }
        $shop_payment->type = $data['type'];
        $shop_payment->save();
        return $shop_payment;
    }

    public function updateShopPaymentByID($data, $shop_payment, $file)
    {   
        $shop_payment->shop_id = $data['shop_id'];
        $shop_payment->amount =  $data['amount'];
        if($file) {
            $file_name = $this->uploadFile($file, 'public', 'shop payment');
            $shop_payment->image = $file_name;
        } else {
            $shop_payment->image = $shop_payment->image;
        }
        $shop_payment->type = $data['type'];
        $shop_payment->save();
    }

    public function deleteShopPaymentByID($id)
    {
        ShopPayment::destroy($id);
    }
}