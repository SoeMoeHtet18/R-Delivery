<?php

namespace App\Repositories;

use App\Models\Rider;
use Illuminate\Support\Facades\Log;

class NotificationRepository {
    public function getNotificationsForRider($id)
    {
        $rider = Rider::find($id);
        $notifications = $rider->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
        return $notifications;
    }
}