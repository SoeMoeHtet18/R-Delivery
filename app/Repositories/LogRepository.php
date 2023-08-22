<?php

namespace App\Repositories;

use App\Models\Log;

class LogRepository {
    public function getLogByOrderID($orderId) {
        return Log::where('order_id', $orderId)->orderBy('id','desc')->get();
    }
}