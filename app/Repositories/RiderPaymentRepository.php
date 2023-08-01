<?php

namespace App\Repositories;

use App\Models\RiderPayment;

class RiderPaymentRepository
{
    public function getAllRiderPaymentQuery()
    {
        $query = RiderPayment::leftJoin('riders', 'riders.id','rider_payments.rider_id')->select('rider_payments.*','riders.name as rider_name');
        return $query;
    }

    public function show($id)
    {
        $data = RiderPayment::with('rider')->findOrFail($id);
        return $data;
    }
    
}