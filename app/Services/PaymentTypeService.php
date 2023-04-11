<?php

namespace App\Services;

use App\Models\PaymentType;

class PaymentTypeService
{
    public function savePaymentTypeData($data)
    {
        $payment_type = new PaymentType();
        $payment_type->name = $data['name'];
        $payment_type->save();
    }

    public function updatePaymentTypeData($data, $payment_type)
    {
        $payment_type->name = $data['name'];
        $payment_type->save();
    }

    public function deletePaymentTypeData($id)
    {
        PaymentType::destroy($id);
    }
}
