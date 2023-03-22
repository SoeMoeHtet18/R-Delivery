<?php

namespace App\Services;

use App\Models\City;
use App\Models\CustomerPayment;

class CustomerPaymentService
{
    public function saveCustomerPayment($data)
    {
        $customer_payment = new CustomerPayment();
        $customer_payment->order_id =  $data['order_id'];
        $customer_payment->amount =  $data['amount'];
        $customer_payment->type =  $data['type'];
        $customer_payment->proof_of_payment =  $data['proof_of_payment'] ?? null;
        $customer_payment->paid_at =  $data['paid_at'] ?? null;
        $customer_payment->last_updated_by =  $data['last_updated_by'] ?? null;
        $customer_payment->save();
        return $customer_payment;
    }

    public function updateCustomerPaymentByID($data,$customer_payment)
    {
        $customer_payment->order_id =  $data['order_id'];
        $customer_payment->amount =  $data['amount'];
        $customer_payment->type =  $data['type'];
        $customer_payment->proof_of_payment =  $data['proof_of_payment'] ?? null;
        $customer_payment->paid_at =  $data['paid_at'] ?? null;
        $customer_payment->last_updated_by =  $data['last_updated_by'] ?? null;
        $customer_payment->save();
    }

    public function deleteCustomerPaymentByID($id)
    {
        CustomerPayment::destroy($id);
    }
}