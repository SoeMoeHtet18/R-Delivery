<?php

namespace App\Services;

use App\Http\Traits\FileUploadTrait;
use App\Models\Order;

class OrderService
{   
    use FileUploadTrait;
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
        $order->quantity =  $data['quantity'];
        $order->delivery_fees =  $data['delivery_fees'];
        $order->total_amount = $data['total_amount'];
        $order->markup_delivery_fees =  $data['markup_delivery_fees'] ?? null;
        $order->remark =  $data['remark'] ?? null;
        $order->status =  $data['status'] ?? "pending";
        $order->item_type =  $data['item_type'];
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? null ;
        $order->type =  $data['type'];
        $order->collection_method =  $data['collection_method'];
        $order->proof_of_payment =  $data['proof_of_payment'] ?? null;
        $order->save();
        return $order;
    }

    public function saveOrderByShopID($data, $shop_id)
    {
        $order = new Order();
        $order->order_code =  $data['order_code'];
        $order->shop_id =  $shop_id;
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
        $order->status =  $data['status'] ?? "pending";
        $order->item_type =  $data['item_type'];
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? null ;
        $order->type =  $data['type'];
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
        $order->status =  $data['status'];
        $order->item_type =  $data['item_type'];
        $order->full_address =  $data['full_address'] ?? null;
        $order->schedule_date =  $data['schedule_date'] ?? null ;
        $order->type =  $data['type'];
        $order->collection_method =  $data['collection_method'];
        if($file) {
            $file_name = $this->uploadFile($file, 'public', 'customer payment');
            $order->proof_of_payment = $file_name;
        } else {
            $order->proof_of_payment =  $order->proof_of_payment;
        }
        $order->save();
        return $order;
    }

    public function deleteOrderByID($id)
    {
        Order::destroy($id);
    }

    public function changeStatus($order, $status)
    {
        $order->status = $status;
        $order->save();
        return $order;
    }

    public function assignRider($order, $data)
    {
        $order->rider_id = $data['rider_id'];
        $order->save();
    }
}