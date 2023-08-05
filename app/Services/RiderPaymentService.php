<?php

namespace App\Services;

use App\Models\RiderPayment;
use Carbon\Carbon;

class RiderPaymentService
{
    public function saveRiderPaymentData($data)
    {
        $user = auth()->user();

        $rider_payment = new RiderPayment();
        $rider_payment->rider_id = $data['rider_id'];
        $rider_payment->type = $data['type'];
        $paid_date = Carbon::now()->format('Y-m-d');
        $rider_payment->paid_date = $paid_date;
        $rider_payment->total_amount =  $data['total_amount'];
        $rider_payment->total_routine = $data['total_routine'];
        $rider_payment->branch_id = $user->branch_id;
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
