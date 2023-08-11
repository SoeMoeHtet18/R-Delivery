<?php

namespace App\Services;

use App\Models\Notifiable;
use App\Models\Notification;
use App\Models\Rider;
use App\Models\Shop;
use App\Models\ShopUser;
use App\Models\User;

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
        // $message = 'Your order ' . $order_code . ' is in warehouse now.';
        // $notification = $this->createNotification('warehouse', $message);
        // $shop_users = ShopUser::where('shop_id', $shop_id)->get();
        // foreach ($shop_users as $shop_user) {
        //     $this->attachNotification($shop_user, $notification);
        // }
    }

    public function orderCreateNotificationForShopUser($shop_id)
    {
        $message = 'Your order is created. Please wait to be picked by our rider';
        $notification = $this->createNotification('create', $message);
        $shop_users = ShopUser::where('shop_id', $shop_id)->get();
        foreach ($shop_users as $shop_user) {
            $this->attachNotification($shop_user, $notification);
        }
    }
    
    public function orderCreateByShopNotificationForUser($shop, $order)
    {
        $message = 'An order ' . $order->order_code . ' was created by ' . $shop->name . '; $order_id = ' . $order->id;
        $notification = $this->createNotification('order create by shop', $message);

        //to notify all user under branch.
        $users = User::where('branch_id',$shop->branch_id)->get();
        foreach ($users as $user) {
            $this->attachNotification($user, $notification);
        }
    }
    
    public function collectionCreateByShopNotificationForUser($shop, $collection)
    {
        $message = 'An collection ' . $collection->collection_code . ' was created by ' . $shop->name . '; $collection_id = ' . $collection->id;
        $notification = $this->createNotification('collection create by shop', $message);

        //to notify all user under branch.
        $users = User::where('branch_id',$shop->branch_id)->get();
        foreach ($users as $user) {
            $this->attachNotification($user, $notification);
        }
    }
    
    public function orderDelayStatusNotificationForUser($order)
    {
        $message = $order->order_code . ' is delay due to "' . $order->note . '".; $order_id = ' . $order->id;
        $notification = $this->createNotification('order delay by rider', $message);

        //to notify all user under branch.
        $users = User::where('branch_id',$order->branch_id)->get();
        foreach ($users as $user) {
            $this->attachNotification($user, $notification);
        }
    }
    
    public function orderCancelRequestStatusNotificationForUser($order)
    {
        $message = $order->rider->name . ' is asking cancel request for ' . $order->order_code . '.; $order_id = ' . $order->id;
        $notification = $this->createNotification('order cancel request by rider', $message);

        //to notify all user under branch.
        $users = User::where('branch_id',$order->branch_id)->get();
        foreach ($users as $user) {
            $this->attachNotification($user, $notification);
        }
    }
}
