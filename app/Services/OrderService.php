<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Traits\FileUploadTrait;
use App\Models\ItemType;
use App\Models\Order;
use App\Models\Township;
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
        $order->quantity =  $data['quantity'] ?? '';
        $order->delivery_fees =  $data['delivery_fees'];
        $order->total_amount = $data['total_amount'];
        $order->markup_delivery_fees =  $data['markup_delivery_fees'] ?? 0;
        $order->remark =  $data['remark'] ?? null;
        $order->status = "pending";
        $order->item_type =  $data['item_type'];
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? null;
        $order->type =  $data['type'];
        $order->collection_method = $data['collection_method'];
        $order->proof_of_payment = $data['proof_of_payment'] ?? null;
        $order->save();
        return $order;
    }

    public function saveOrderByShopID($data, $shop_id)
    {
        $delivery_fees = Township::where('id', $data['township_id'])->first()->delivery_fees;
        $itemType = ItemType::find($data['item_type']);
        $order = new Order();
        $orderCode = Helper::nomenclature('orders', 'OD', 'id', $shop_id);
        $order->order_code =  $orderCode;
        $order->shop_id =  $shop_id;
        $order->customer_name =  $data['customer_name'];
        $order->customer_phone_number =  $data['customer_phone_number'];
        $order->city_id =  $data['city_id'];
        $order->township_id =  $data['township_id'];
        $order->items =  $data['items'] ?? '';
        $order->quantity =  $data['quantity'] ?? '';
        $order->delivery_fees =  $delivery_fees;
        $order->total_amount = $data['total_amount'];
        $order->markup_delivery_fees =  $data['markup_delivery_fees'] ?? 0;
        $order->remark =  $data['remark'] ?? null;
        $order->status = "pending";
        $order->item_type =  $itemType->name;
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? null;
        $order->type =  $data["type"];
        $order->collection_method =  $data['collection_method'];
        $order->proof_of_payment =  $data['proof_of_payment'] ?? null;
        $order->save();
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
        $order->quantity =  $data['quantity'];
        $order->delivery_fees =  $data['delivery_fees'];
        $order->total_amount = $data['total_amount'];
        $order->markup_delivery_fees =  $data['markup_delivery_fees'] ?? null;
        $order->remark =  $data['remark'] ?? null;
        $this->changeStatus($order, $data['status']);
        if ($data['status'] == 'cancel') {
            $this->notificationService->orderCancelNotificationForRider($order->rider_id, $order->order_code);
            $this->notificationService->orderCancelNotificationForShopUsers($order->shop_id, $order->order_code);
        }
        if ($data['status'] == 'delay') {
            $this->notificationService->orderIssueNotificationForShopUsers($order->shop_id, $order->order_code);
        }
        if ($data['status'] == 'success') {
            $this->notificationService->orderArrivalNotificationForShopUsers($order->shop_id, $order->order_code);
        }
        $order->item_type =  $data['item_type'];
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? null;
        $order->type =  $data['type'];
        $order->collection_method =  $data['collection_method'];
        if ($file) {
            $file_name = $this->uploadFile($file, 'public', 'customer payment');
            $order->proof_of_payment = $file_name;
        } else {
            $order->proof_of_payment =  $order->proof_of_payment;
        }
        $order->last_updated_by = auth()->user()->id;
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
            } elseif ($order->status == 'delay') {
                $notificationMethod = 'orderIssueNotificationFor';
                $updateField = 'delayed_at';
            } elseif ($order->status == 'success') {
                $notificationMethod = 'orderArrivalNotificationFor';
                $updateField = 'delivered_at';
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
}
