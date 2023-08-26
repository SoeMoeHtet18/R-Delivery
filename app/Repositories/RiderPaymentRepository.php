<?php

namespace App\Repositories;

use App\Models\RiderPayment;

class RiderPaymentRepository
{
    public function getAllRiderPaymentQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = RiderPayment::leftJoin('riders', 'riders.id','rider_payments.rider_id')->where('rider_payments.branch_id', $branch_id)->select('rider_payments.*','riders.name as rider_name');
        return $query;
    }

    public function show($id)
    {
        $data = RiderPayment::with('rider')->findOrFail($id);
        return $data;
    }
    
    public function getAllRiderPaymentCount()
    {
        $user = auth()->user();
        return RiderPayment::where('branch_id', $user->branch_id)->count();
    }
}