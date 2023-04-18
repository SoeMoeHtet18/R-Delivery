<?php

namespace App\Repositories;

use App\Models\PaymentType;

class PaymentTypeRepository
{
    public function getAllPaymentTypeQuery()
    {
        $payment_type = PaymentType::select('*');
        return $payment_type;
    }

    public function getPaymentTypeByID($id)
    {
        $payment_type = PaymentType::findOrFail($id);
        return $payment_type;
    }

    public function getPaymentTypeList()
    {
        $payment_type = PaymentType::get();
        return $payment_type;
    }
}