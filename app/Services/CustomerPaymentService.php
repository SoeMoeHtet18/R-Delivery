<?php

namespace App\Services;

use App\Http\Traits\FileUploadTrait;
use App\Models\CustomerPayment;

class CustomerPaymentService
{
    use FileUploadTrait;

    public function saveCustomerPayment($data, $file)
    {
        $customer_payment = new CustomerPayment();
        $customer_payment->order_id =  $data['order_id'];
        $customer_payment->amount =  $data['amount'];
        $customer_payment->type =  $data['type'];
        if($file) {
            $file_name = $this->uploadFile($file, 'public', 'customer payment');
            $customer_payment->proof_of_payment =  $file_name;
        } else {
            $customer_payment->proof_of_payment = null;
        }
        $customer_payment->paid_at =  $data['paid_at'] ?? null;
        $customer_payment->last_updated_by =  $data['last_updated_by'] ?? null;
        $customer_payment->description = $data['description'] ?? null;
        $customer_payment->save();
        return $customer_payment;
    }

    public function updateCustomerPaymentByID($data,$customer_payment, $file)
    {
        $customer_payment->order_id =  $data['order_id'];
        $customer_payment->amount =  $data['amount'];
        $customer_payment->type =  $data['type'];
        if($file) {
            $file_name = $this->uploadFile($file, 'public', 'customer payment');
            $customer_payment->proof_of_payment =  $file_name;
        } else {
            $customer_payment->proof_of_payment = $customer_payment->proof_of_payment;
        }
        $customer_payment->paid_at =  $data['paid_at'] ?? null;
        $customer_payment->last_updated_by =  $data['last_updated_by'] ?? null;
        $customer_payment->description = $data['description'];
        $customer_payment->save();
    }

    public function deleteCustomerPaymentByID($id)
    {
        CustomerPayment::destroy($id);
    }
}