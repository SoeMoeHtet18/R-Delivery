<?php

namespace App\Repositories;

use App\Models\Rider;

class NotificationRepository {
    public function getNotificationsForRider($id)
    {
        $rider = Rider::find($id);
        $notifications = $rider->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
        return $notifications;
    }
    public function getNotificationCountForRider($id)
    {
        $rider = Rider::find($id);
        $notifications = $rider->notifications()->where('is_read', 0)->count();
        return $notifications;
    }
}