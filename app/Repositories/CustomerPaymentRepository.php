<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\CustomerPayment;

class CustomerPaymentRepository
{
    public function getAllCustomerPaymentsQuery()
    {
        $query = CustomerPayment::leftJoin('orders','orders.id','customer_payments.order_id')->select('customer_payments.*','orders.order_code');
        return $query;
    }
    public function getCustomerPaymentByID($id)
    {
        $customer_payment = CustomerPayment::where('id',$id)->first();
        return $customer_payment;
    }
}