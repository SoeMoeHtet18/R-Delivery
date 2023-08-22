<?php

namespace App\Services;

use App\Models\Log;

class LogService {
    public function saveLog($orderId, $fromStatus, $toStatus, $updatedUserId, $updatedType)
    {
        $log = new Log();
        $log->order_id = $orderId;
        $log->from_status = $fromStatus;
        $log->to_status = $toStatus;
        $log->updated_by = $updatedUserId;
        $log->updated_type = $updatedType;
        $log->save();
    }
}