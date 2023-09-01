<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Traits\FileUploadTrait;
use App\Models\ItemType;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Shop;
use App\Models\Township;
use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    use FileUploadTrait;

    protected $notificationService;
    protected $logService;

    public function __construct(NotificationService $notificationService, LogService $logService)
    {
        $this->notificationService = $notificationService;
        $this->logService = $logService;
    }
    public function saveOrderData($data)
    {
        $user = auth()->user();
        $today = Carbon::today()->format('Y-m-d');
        
        $order = new Order();
        $order->order_code =  $data['order_code'];
        $order->shop_id =  $data['shop_id'];
        $order->customer_name =  $data['customer_name'];
        $order->customer_phone_number =  $data['customer_phone_number'];
        $order->city_id =  $data['city_id'];
        $order->township_id =  $data['township_id'];
        $order->rider_id =  $data['rider_id'] ?? null;
        $order->items =  $data['items'] ?? null;
        $order->quantity =  $data['quantity'] ?? null;
        $order->delivery_fees =  $data['delivery_fees'];
        $order->total_amount = $data['total_amount'];
        $order->markup_delivery_fees =  $data['markup_delivery_fees'] ?? 0;
        $order->remark =  $data['remark'] ?? null;
        $order->status = "pending";
        $order->item_type_id =  $data['item_type_id'] ?? null;
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? Carbon::tomorrow();;
        // $order->type =  $data['type'] ?? null;
        $order->collection_method = $data['collection_method'];
        $order->proof_of_payment = $data['proof_of_payment'] ?? null;
        $order->payment_method = $data['payment_method'] ?? null;
        $order->note = $data['note'] ?? null;
        $order->delivery_type_id = $data['delivery_type_id'];
        $order->is_payment_channel_confirm = 0;
        $order->is_confirm = 0;
        $order->extra_charges = $data['extra_charges'] ?? 0;
        $order->branch_id = $user->branch_id;
        // $order->pay_later = $data['total_amount'] > 100000 ? true : false;
        $order->pay_later = isset($data['pay_later']) ? true : false;
        $order->is_deli_free = isset($data['is_deli_free']) ? true : false;
        $order->save();
        if($today == Carbon::parse($data['schedule_date'])->format('Y-m-d')) {
            $this->changeStatus($order, 'delivering', $user, User::class);
        }
        return $order;
    }

    public function saveOrderByShopID($data, $shop_id)
    {
        $shop = Shop::where('id', $shop_id)->first();
        $delivery_fees = Township::where('id', $data['township_id'])->first()->delivery_fees;

        $order = new Order();
        $orderCode = Helper::nomenclature('orders', 'TCP', 'id', $shop_id, 'S');
        $order->order_code =  $orderCode;
        $order->shop_id =  $shop_id;
        $order->customer_name =  $data['customer_name'];
        $order->customer_phone_number =  $data['customer_phone_number'];
        $order->city_id =  $data['city_id'];
        $order->township_id =  $data['township_id'];
        $order->items =  $data['items'] ?? null;
        $order->quantity =  $data['quantity'] ?? null;
        $order->delivery_fees =  $delivery_fees;
        $order->total_amount = $data['total_amount'];
        $order->markup_delivery_fees =  $data['markup_delivery_fees'] ?? 0;
        $order->remark =  $data['remark'] ?? null;
        $order->status = "pending";
        $order->item_type_id =  $data['item_type_id'] ?? null;
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? Carbon::tomorrow();;
        // $order->type =  $data["type"];
        $order->collection_method =  $data['collection_method'];
        $order->proof_of_payment =  $data['proof_of_payment'] ?? null;
        $order->payment_method = $data['payment_method'];
        $order->delivery_type_id = $data['delivery_type_id'];
        $order->is_payment_channel_confirm = 0;
        $order->is_confirm = 0;
        $order->extra_charges = $data['extra_charges'] ?? 0;
        $order->branch_id = $shop->branch_id;
        $order->pay_later = $data['total_amount'] > 100000 ? true : false;
        $order->save();
        $this->notificationService->orderCreateNotificationForShopUser($shop_id);
        $this->notificationService->orderCreateByShopNotificationForUser($shop, $order);
        return $order;
    }

    public function updateOrderByID($data, $order, $file)
    {
        $user = auth()->user();
        $order->shop_id =  $data['shop_id'];
        $order->customer_name =  $data['customer_name'];
        $order->customer_phone_number =  $data['customer_phone_number'];
        $order->city_id =  $data['city_id'];
        $order->township_id =  $data['township_id'];
        $order->rider_id =  $data['rider_id'] ?? null;
        $order->items =  $data['items'] ?? null;
        $order->quantity =  $data['quantity'] ?? null;
        $order->delivery_fees =  $data['delivery_fees'];
        $order->total_amount = $data['total_amount'];
        $order->markup_delivery_fees =  $data['markup_delivery_fees'] ?? 0.00;
        $order->remark =  $data['remark'] ?? null;
        $this->changeStatus($order, $data['status'], $user, User::class);
        // if ($data['status'] == 'cancel') {
        //     $this->notificationService->orderCancelNotificationForRider($order->rider_id, $order->order_code);
        //     $this->notificationService->orderCancelNotificationForShopUsers($order->shop_id, $order->order_code);
        // }
        // if ($data['status'] == 'delay') {
        //     $this->notificationService->orderIssueNotificationForShopUsers($order->shop_id, $order->order_code);
        // }
        // if ($data['status'] == 'success') {
        //     $this->notificationService->orderArrivalNotificationForShopUsers($order->shop_id, $order->order_code);
        // }
        // if ($data['status'] == 'warehouse') {
        //     $this->notificationService->orderInWarehouseNotificationForShopUsers($order->shop_id, $order->order_code);
        // }
        $order->item_type_id =  $data['item_type_id'] ?? null;
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? Carbon::tomorrow();;
        // $order->type =  $data['type'];
        $order->collection_method =  $data['collection_method'];
        $order->payment_method = $data['payment_method'] ?? null;
        $order->note = $data['note'] ?? $order->note;
        if ($file) {
            $file_name = $this->uploadFile($file, 'public', 'customer payment');
            $order->proof_of_payment = $file_name;
        } else {
            $order->proof_of_payment =  $order->proof_of_payment;
        }
        $order->last_updated_by = auth()->user()->id;
        $order->extra_charges = $data['extra_charges'] ?? 0;
        $order->delivery_type_id = $data['delivery_type_id'];
        // $order->pay_later = $data['total_amount'] > 100000 ? true : false;
        $order->pay_later = isset($data['pay_later']) ? true : false;
        $order->is_deli_free = isset($data['is_deli_free']) ? true : false;
        $order->save();
        return $order;
    }

    public function deleteOrderByID($id)
    {
        Order::destroy($id);
    }

    public function changeStatus($order, $status, $updatedUser, $updatedType)
    {
        if ($order->status != $status) {
            // apporach Tracking For Order Status
            if($order->status == 'pending' && $status == 'warehouse' || $status == 'delivering') {
                $this->logService->saveLog($order->id, 'pending', 'picking-up', $updatedUser->id, $updatedType);
                $order->update(['status' => 'picking-up']);
            }
            if($order->status == 'pending' || $order->status == 'picking-up' && $status == 'delivering') {
                $this->logService->saveLog($order->id, 'picking-up', "warehouse", $updatedUser->id, $updatedType);
                $order->update(['status' => 'warehouse']);
            }

            $this->logService->saveLog($order->id, $order->status, $status, $updatedUser->id, $updatedType);

            // Assuming $order is an instance of Order model and $status is the new status
            $order->update(['status' => $status]);

            // make notification
            $notificationMethod = '';
            $updateField = '';

            if ($order->status == 'cancel') {
                $notificationMethod = 'orderCancelNotificationFor';
                $updateField = 'canceled_at';
                $this->notificationService->{$notificationMethod . 'Rider'}($order->rider_id, $order->order_code);
                $this->notificationService->{$notificationMethod . 'ShopUsers'}($order->shop_id, $order->order_code);
                $title = 'payable or not';
                $message = 'Please confirm to subtract remaining amount or not for ' . $order->order_code . '; $order_id = ' . $order->id;
                $notification = $this->notificationService->createNotification($title, $message);
                $users = User::get();
                foreach ($users as $user) {
                    $this->notificationService->attachNotification($user, $notification);
                }
            } elseif ($order->status == 'delay') {
                $notificationMethod = 'orderIssueNotificationFor';
                $updateField = 'delayed_at';
            } elseif ($order->status == 'success') {
                $notificationMethod = 'orderArrivalNotificationFor';
                $updateField = 'delivered_at';
            } elseif ($order->status == 'warehouse') {
                $notificationMethod = 'orderInWarehouseNotificationFor';
                $updateField = 'in_warehouse';
            } elseif ($order->status == 'cancel_request') {
                $updateField = 'cancel_request';
                $this->notificationService->orderCancelRequestStatusNotificationForUser($order);
            }

            if ($notificationMethod != 'orderCancelNotificationFor' && !empty($updateField) && $order->status != 'cancel_request') {
                $this->notificationService->{$notificationMethod . 'ShopUsers'}($order->shop_id, $order->order_code);
            }

            return $order;
        }
    }


    public function assignRider($order, $data)
    {
        $order->rider_id = $data['rider_id'];
        $order->township_id = $data['township_id'];
        $order->status = 'delivering';
        $order->save();
        $notification = $this->notificationService->orderCreateNotificationForRider($order->rider_id);
        $orders = [];
        if (Storage::exists('order_data.txt')) {
            $orderDataJson = Storage::get('order_data.txt');
            $orders = json_decode($orderDataJson, true);
        }

        $orderId = $order->order_code;
        $orders[$orderId]['picked_at'] = $order->updated_at;

        $orderDataJson = json_encode($orders);
        Storage::put('order_data.txt', $orderDataJson);
        return $order;
    }

    public function uploadProofOfPayment($order, $image)
    {
        if ($image) {
            $file_name = $this->uploadFile($image, 'public', 'customer payment');
            $order->update(['proof_of_payment' => $file_name]);
            return $file_name;
        }
    }

    public function updateOrderScheduleDateByRider($data, $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $order->schedule_date = $data['schedule_date'];
        $order->note = $data['remark'];
        $order->status = 'delay';
        $order->save();
        $this->notificationService->orderDelayStatusNotificationForUser($order);
        $orders = [];
        if (Storage::exists('order_data.txt')) {
            $orderDataJson = Storage::get('order_data.txt');
            $orders = json_decode($orderDataJson, true);
        }

        $orderId = $order->order_code;
        $orders[$orderId]['delayed_at'] = $order->updated_at;

        $orderDataJson = json_encode($orders);
        Storage::put('order_data.txt', $orderDataJson);
        return $order;
    }

    public function updatePaymentChannel($data, $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $order->payment_channel = $data['payment_channel'];
        if ($data['payment_channel'] == 'cash') {
            $order->is_payment_channel_confirm = true;
        } else {
            $order->is_payment_channel_confirm = false;
            $title = 'payment channel confirm';
            $message = 'Please confirm payment channel for ' . $order->order_code . '; $order_id = ' . $order->id;
            $notification = $this->notificationService->createNotification($title, $message);
            $users = User::get();
            foreach ($users as $user) {
                $this->notificationService->attachNotification($user, $notification);
            }
        }
        $order->save();
        return $order;
    }

    public function confirmRemainingAmount($order)
    {
        $order->payable_or_not = 'yes';
        $order->save();
        return $order;
    }

    public function confirmPaymentChannel($order)
    {
        $order->is_payment_channel_confirm = true;
        $order->save();
        return $order;
    }

    public function cancelRemainingAmount($order)
    {
        $order->payable_or_not = 'no';
        $order->save();
        return $order;
    }

    public function bulkDiscountUpdate($data)
    {
        $order_ids = $data['order_ids'];
        $order_ids = explode(',', $order_ids);
        $type = $data['bulk-discount-type'];
        $amount = $data['amount'];
        $orders = Order::whereIn('id', $order_ids)->get();
        foreach ($orders as $order) {
            if ($type == 'normal_discount') {
                $order->discount = $amount;
                $order->save();
            } else {
                if ($order->delivery_fees > $amount) {
                    $amount_to_subtract = $amount - $order->delivery_fees;
                    $amount_to_subtract = abs($amount_to_subtract);
                } else {
                    $amount_to_subtract = 0;
                }
                $order->discount = $amount_to_subtract;
                $order->save();
            }
        }
    }

    public function assignCollectionGroupToOrder($id, $collection_group_id)
    {
        $order = Order::where('id',$id)->firstOrFail();
        $order->collection_group_id = $collection_group_id;
        $order->save();
        return $order;
    }

    public function assignCollectionGroupToOrders($order_ids, $collection_group_id)
    {
        $order_ids = explode(',', $order_ids);
        $orders = Order::whereIn('id',$order_ids)->get();
        foreach($orders as $order) {
            $order->collection_group_id = $collection_group_id;
            $order->save();
        }
        return $orders;
    }

    public function cancelPaymentChannel($order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $order->payment_channel = null;
        $order->is_payment_channel_confirm = false;
        $order->save();
        return $order;
    }

    /**
     * re assign rider and to notify to admin
     */
    public function reAssignRider($order, $rider)
    {
        $today   = Carbon::today()->format('Y-m-d');
        $riderId = $rider->id;
        $order->rider_id      = $riderId;
        $order->schedule_date = $today;
        $order->status        = 'delivering';
        $order->save();

        //to notify to admin about rider takes over order
        $this->notificationService->riderReassignForOrderNotificationForUser($order, $rider);

        return $order;

    }
}
