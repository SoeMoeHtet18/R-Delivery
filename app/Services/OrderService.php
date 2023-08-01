<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Traits\FileUploadTrait;
use App\Models\ItemType;
use App\Models\Order;
use App\Models\Township;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    use FileUploadTrait;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function saveOrderData($data)
    {
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
        $order->save();
        return $order;
    }

    public function saveOrderByShopID($data, $shop_id)
    {
        $delivery_fees = Township::where('id', $data['township_id'])->first()->delivery_fees;

        $order = new Order();
        $orderCode = Helper::nomenclature('orders', 'OD', 'id', $shop_id);
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
        $order->save();
        $this->notificationService->orderCreateNotificationForShopUser($shop_id);
        return $order;
    }

    public function updateOrderByID($data, $order, $file)
    {
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
        $order->markup_delivery_fees =  $data['markup_delivery_fees'] ?? null;
        $order->remark =  $data['remark'] ?? null;
        $this->changeStatus($order, $data['status']);
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
        $order->note = $data['note'];
        if ($file) {
            $file_name = $this->uploadFile($file, 'public', 'customer payment');
            $order->proof_of_payment = $file_name;
        } else {
            $order->proof_of_payment =  $order->proof_of_payment;
        }
        $order->last_updated_by = auth()->user()->id;
        $order->extra_charges = $data['extra_charges'] ?? 0;
        $order->delivery_type_id = $data['delivery_type_id'];
        $order->save();
        return $order;
    }

    public function deleteOrderByID($id)
    {
        Order::destroy($id);
    }

    public function changeStatus($order, $status)
    {
        if ($order->status != $status) {
            $order->status = $status;
            $order->save();

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
            }

            if ($notificationMethod != 'orderCancelNotificationFor' && !empty($updateField)) {
                $this->notificationService->{$notificationMethod . 'ShopUsers'}($order->shop_id, $order->order_code);
            }

            $orders = [];
            if (Storage::exists('order_data.txt')) {
                $orderDataJson = Storage::get('order_data.txt');
                $orders = json_decode($orderDataJson, true);
            }

            $orderId = $order->order_code;
            $orders[$orderId][$updateField] = $order->updated_at;

            $orderDataJson = json_encode($orders);
            Storage::put('order_data.txt', $orderDataJson);

            return $order;
        }
    }


    public function assignRider($order, $data)
    {
        $order->rider_id = $data['rider_id'];
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
        // $this->notificationService->orderIssueNotificationForShopUsers($order->shop_id, $order->order_code);
        // $orders = [];
        // if (Storage::exists('order_data.txt')) {
        //     $orderDataJson = Storage::get('order_data.txt');
        //     $orders = json_decode($orderDataJson, true);
        // }

        // $orderId = $order->order_code;
        // $orders[$orderId]['delayed_at'] = $order->updated_at;

        // $orderDataJson = json_encode($orders);
        // Storage::put('order_data.txt', $orderDataJson);

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
                    $amount_to_substract = $amount - $order->delivery_fees;
                    $amount_to_substract = abs($amount_to_substract);
                } else {
                    $amount_to_substract = 0;
                }
                $order->discount = $amount_to_substract;
                $order->save();
            }
        }
    }
}
