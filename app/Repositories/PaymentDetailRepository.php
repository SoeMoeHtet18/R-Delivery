<?php

namespace App\Repositories;

use App\Models\PaymentDetail;

class PaymentDetailRepository{

    public function getPaymentDetailByShop($shop_id)
    {
        return PaymentDetail::where('shop_id', $shop_id)
            ->leftJoin('payment_types', 'payment_types.id', 'payment_details.payment_type_id')
            ->select('payment_details.*', 'payment_types.name as payment_type_name')
            ->firstOrFail();
    }
}