<?php

namespace App\Repositories;

class NotificationRepository
{
    public function getNotifications($model, $id)
    {
        $user = $model::find($id);
        $notifications = $user->notifications()
            ->where('notifiables.deleted_at', null)
            ->orderBy('created_at', 'desc')
            ->withPivot('is_read')
            ->get();
        return $notifications;
    }

    public function getNotificationCount($model, $id)
    {
        $user = $model::find($id);
        if (!$user) {
            return 0;
        }
        $count = $user->notifications()->wherePivot('is_read', 0)->count();
        return $count;
    }
}
