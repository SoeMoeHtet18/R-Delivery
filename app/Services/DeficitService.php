<?php

namespace App\Services;

use App\Models\Deficit;

class DeficitService {
    public function addDeficitToRider($data) 
    {
        $deficit = new Deficit();
        $deficit->rider_id = $data['rider_id'];
        $deficit->total_amount = $data['amount'];
        $deficit->save();
    }
}