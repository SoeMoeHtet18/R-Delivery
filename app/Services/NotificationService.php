<?php

namespace App\Services;

use App\Models\Notifiable;
use App\Models\Notification;
use App\Models\Rider;

class NotificationService {

    public function orderCreateNotificationForRider($id) 
    {
        $notification = new Notification();
        $notification->title = 'create';
        $notification->message = 'You have got a new order.';
        $notification->save();
        $rider = Rider::find($id);
        $rider->notifications()->attach($notification->id);
    }

    public function orderCancelNotificationForRider($id, $order_code) 
    {
        $notification = new Notification();
        $notification->title = 'cancel';
        $notification->message = 'Your order ' . $order_code . ' is canceled.';
        $notification->save();
        $rider = Rider::find($id);
        $rider->notifications()->attach($notification->id);
    }

    public function removeNotificationByRider($id, $rider_id)
    {
        $notification = Notification::findOrFail($id);
        $notifiable = Notifiable::where('notification_id',$id)
            ->where('notifiable_id', $rider_id)
            ->where('notifiable_type', Rider::class)->delete();
        $notification->delete();
        $rider = Rider::find($rider_id);
        $notifications = $rider->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
        return $notifications;
    }

    public function makeNoticationReadByRider($id, $rider_id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = 1;
        $notification->save();
        $rider = Rider::find($rider_id);
        $notifications = $rider->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
        return $notifications;
    }
}