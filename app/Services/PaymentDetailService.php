<?php

namespace App\Services;

use App\Models\PaymentDetail;

class PaymentDetailService {

    public function createPaymentDetail($data)
    {
        $paymentDetail = new PaymentDetail();
        $paymentDetail->shop_id = $data['shop_id'];
        $paymentDetail->payment_type_id = $data['payment_type_id'];
        $paymentDetail->account_owner_name = $data['account_owner_name'];
        $paymentDetail->account_detail = $data['account_number'];
        $paymentDetail->save();

        return $paymentDetail;
    }

    public function updatePaymentDetail($data, $paymentDetail)
    {
        $paymentDetail->shop_id = $data['shop_id'];
        $paymentDetail->payment_type_id = $data['payment_type_id'];
        $paymentDetail->account_owner_name = $data['account_owner_name'];
        $paymentDetail->account_detail = $data['account_number'];
        $paymentDetail->save();

        return $paymentDetail;
    }
}