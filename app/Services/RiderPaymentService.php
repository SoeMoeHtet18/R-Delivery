<?php

namespace App\Services;

use App\Models\RiderPayment;

class RiderPaymentService
{
    public function saveRiderPaymentData($data)
    {
        $rider_payment = new RiderPayment();
        $rider_payment->rider_id = $data['rider_id'];
        $rider_payment->total_amount =  $data['total_amount'];
        $rider_payment->total_routine = $data['total_routine'];
        $rider_payment->save();
    }

    public function updateRiderPaymentByID($data, $rider_payment)
    {
        $rider_payment->rider_id = $data['rider_id'];
        $rider_payment->total_amount =  $data['total_amount'];
        $rider_payment->total_routine = $data['total_routine'];
        $rider_payment->save();
    }

    public function deleteRiderPaymentByID($id)
    {
        RiderPayment::destroy($id);
    }
}
