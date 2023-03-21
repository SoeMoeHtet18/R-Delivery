<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\CustomerPayment;

class CustomerPaymentRepository
{
    public function getCustomerPaymentByID($id)
    {
        $customer_payment = CustomerPayment::where('id',$id)->first();
        return $customer_payment;
    }
}