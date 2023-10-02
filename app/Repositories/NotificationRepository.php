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

    public function getUnreadNotificationCount($model, $id)
    {
        $user = $model::find($id);
        if (!$user) {
            return '0';
        }
        return $user->notifications()
            ->whereNull('deleted_at')
            ->wherePivot('is_read', 0)
            ->count();
    }
}
