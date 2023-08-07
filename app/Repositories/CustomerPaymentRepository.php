<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\CustomerPayment;

class CustomerPaymentRepository
{
    public function getAllCustomerPaymentsQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = CustomerPayment::leftJoin('orders','orders.id','customer_payments.order_id')->where('customer_payments.branch_id', $branch_id)->select('customer_payments.*','orders.order_code');
        return $query;
    }
    public function getCustomerPaymentByID($id)
    {
        $customer_payment = CustomerPayment::where('id',$id)->first();
        return $customer_payment;
    }

    public function getCustomerPaymentList()
    {
        $data = CustomerPayment::get();
        return $data;
    }

    public function getAllCustomerPaymentCount()
    {
        $count = CustomerPayment::count();
        return $count;
    }
}