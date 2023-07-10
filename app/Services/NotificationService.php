<?php

namespace App\Services;

use App\Models\Notifiable;
use App\Models\Notification;
use App\Models\Rider;
use App\Models\Shop;
use App\Models\ShopUser;

class NotificationService
{
    public function createNotification($title, $message)
    {
        $notification = new Notification();
        $notification->title = $title;
        $notification->message = $message;
        $notification->save();
        return $notification;
    }

    public function attachNotification($user, $notification)
    {
        $user->notifications()->attach($notification->id);
    }

    public function removeNotification($id, $user_id, $userType)
    {
        $notification = Notifiable::where('notification_id', $id)->where('notifiable_id', $user_id)->where('notifiable_type', $userType)->first();
        $notification->delete();
    }

    public function markNotificationAsRead($id, $user_id, $userType)
    {
        $notification = Notifiable::where('notification_id', $id)->where('notifiable_id', $user_id)->where('notifiable_type', $userType)->first();
        $notification->is_read = 1;
        $notification->save();
    }

    public function getNotificationsForUser($model, $id)
    {
        $user = $model::find($id);
        $notifications = $user->notifications()
            ->where('notifiables.deleted_at', null)
            ->orderBy('created_at', 'desc')
            ->withPivot('is_read')
            ->get();
        return $notifications;
    }

    public function orderCreateNotificationForRider($id)
    {
        $notification = $this->createNotification('create', 'You have got a new order.');
        $rider = Rider::find($id);
        $this->attachNotification($rider, $notification);
    }

    public function orderCancelNotificationForRider($id, $order_code)
    {
        $message = 'Your order ' . $order_code . ' is canceled.';
        $notification = $this->createNotification('cancel', $message);
        $rider = Rider::find($id);
        if ($rider) {
            $this->attachNotification($rider, $notification);
        }
    }

    public function orderCancelNotificationForShopUsers($shop_id, $order_code)
    {
        $message = 'Your order ' . $order_code . ' is canceled.';
        $notification = $this->createNotification('cancel', $message);
        $shop_users = ShopUser::where('shop_id', $shop_id)->get();
        foreach ($shop_users as $shop_user) {
            $this->attachNotification($shop_user, $notification);
        }
    }

    public function orderArrivalNotificationForShopUsers($shop_id, $order_code)
    {
        $message = 'Your order ' . $order_code . ' is delivered.';
        $notification = $this->createNotification('arrive', $message);
        $shop_users = ShopUser::where('shop_id', $shop_id)->get();
        foreach ($shop_users as $shop_user) {
            $this->attachNotification($shop_user, $notification);
        }
    }

    public function orderIssueNotificationForShopUsers($shop_id, $order_code)
    {
        $message = 'Your order ' . $order_code . ' might delay due to traffic.';
        $notification = $this->createNotification('delay', $message);
        $shop_users = ShopUser::where('shop_id', $shop_id)->get();
        foreach ($shop_users as $shop_user) {
            $this->attachNotification($shop_user, $notification);
        }
    }

    public function removeNotificationByUser($id, $user_id, $userType)
    {
        $this->removeNotification($id, $user_id, $userType);
        $notifications = $this->getNotificationsForUser($userType, $user_id);
        return $notifications;
    }

    public function makeNotificationReadByUser($id, $user_id, $userType)
    {
        $this->markNotificationAsRead($id, $user_id, $userType);
        $notifications = $this->getNotificationsForUser($userType, $user_id);
        return $notifications;
    }

    public function orderInWarehouseNotificationForShopUsers($shop_id, $order_code)
    {
        $message = 'Your order ' . $order_code . ' is in warehouse now.';
        $notification = $this->createNotification('warehouse', $message);
        $shop_users = ShopUser::where('shop_id', $shop_id)->get();
        foreach ($shop_users as $shop_user) {
            $this->attachNotification($shop_user, $notification);
        }
    }
}
