<?php

namespace App\Repositories;

class NotificationRepository
{
    public function getNotifications($model, $id)
    {
        $user = $model::find($id);
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
        return $notifications;
    }

    public function getNotificationCount($model, $id)
    {
        $user = $model::find($id);
        $count = $user->notifications()->where('is_read', 0)->count();
        return $count;
    }
}
