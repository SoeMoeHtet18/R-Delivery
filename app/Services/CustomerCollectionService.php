<?php

namespace App\Services;

use App\Models\CustomerCollection;
use App\Helpers\Helper;
use App\Http\Traits\FileUploadTrait;
use App\Models\Order;
use App\Models\Shop;
use Carbon\Carbon;

class CustomerCollectionService
{
    use  FileUploadTrait;
    public function createCustomerCollection($data)
    {
        $user = auth()->user();
        if(isset($data['customer_collection_code'])) {
            $customer_collection_code = $data['customer_collection_code'];
        } else {
            $customer_collection_code =  Helper::nomenclature('customer_collections', 'CE', 'id', $data['shop_id'], 'S');
        }
       
       
        $customer_collection = new CustomerCollection();
        $customer_collection->customer_collection_code = $customer_collection_code;
        $customer_collection->order_id = $data['order_id'] ?? null;
        $customer_collection->items = $data['items'] ?? null;
        $customer_collection->paid_amount = $data['paid_amount'];
        $customer_collection->is_way_fees_payable = $data['is_way_fees_payable'];
        $customer_collection->status = 'pending';
        $customer_collection->note = $data['note'] ?? null;
        $customer_collection->branch_id = $user->branch_id;
        $customer_collection->customer_name = $data['customer_name'];
        $customer_collection->customer_phone_number = $data['phone_number'];
        $customer_collection->shop_id = $data['shop_id'];
        $customer_collection->rider_id = $data['rider_id'] ?? null;
        $customer_collection->city_id = $data['city_id'];
        $customer_collection->township_id = $data['township_id'];
        $customer_collection->address = $data['address'];
        $customer_collection->schedule_date = Carbon::parse($data['schedule_date']);
        $customer_collection->pending_at = Carbon::now();
        $customer_collection->save();
    }

    public function updateCustomerCollection($data, $id)
    {
        $customer_collection = CustomerCollection::where('id', $id)->first();
        $customer_collection->customer_collection_code = $data['customer_collection_code'];
        $customer_collection->order_id = $data['order_id'];
        $customer_collection->items = $data['items'];
        $customer_collection->paid_amount = $data['paid_amount'];
        $customer_collection->is_way_fees_payable = $data['is_way_fees_payable'];
        $customer_collection->status = $data['status'];
        $customer_collection->note = $data['note'];
        $customer_collection->customer_name = $data['customer_name'];
        $customer_collection->customer_phone_number = $data['phone_number'];
        $customer_collection->shop_id = $data['shop_id'];
        $customer_collection->rider_id = $data['rider_id'];
        $customer_collection->city_id = $data['city_id'];
        $customer_collection->township_id = $data['township_id'];
        $customer_collection->address = $data['address'];
        $customer_collection->schedule_date = $data['schedule_date'] ? Carbon::parse($data['schedule_date']) : null;
        if($data['status'] == 'pending') {
            $customer_collection->pending_at = Carbon::now();
        } elseif($data['status'] == 'in-warehouse') {
            $customer_collection->warehouse_at = Carbon::now();
        } elseif($data['status'] == 'complete') {
            $customer_collection->complete_at = Carbon::now();
        }
        $customer_collection->save();
    }

    public function deleteCustomerCollectionByID($id)
    {
        CustomerCollection::destroy($id);
    }

    
    public function saveCustomerCollectionByRider($rider,$data)
    {
        $customer_collection = new CustomerCollection();
        $customer_collection->order_id = $data['order_id'];
        $order = Order::where('id',$data['order_id'])->first();
        $customer_collection_code = Helper::nomenclature('customer_collections', 'CE', 'id', $order->shop_id, 'S');
        $customer_collection->customer_collection_code  = $customer_collection_code;
        $customer_collection->paid_amount  = $data['amount'];
        $customer_collection->note   = $data['reason'];
        $customer_collection->status   = 'pending';
        $customer_collection->pending_at = Carbon::now();
        $file_name = null;
        if ($data['photo']) {
            $photo = $data['photo'];
            $file_name = $this->uploadFile($photo, 'public', 'customer_collection');
            $imageUrl = asset('/storage/customer_collection/' . $file_name);
        }
        $customer_collection->item_image = $file_name;
        $customer_collection->branch_id = $rider->branch_id;
        $customer_collection->customer_name = $order->customer_name;
        $customer_collection->customer_phone_number = $order->customer_phone_number;
        $customer_collection->shop_id = $order->shop_id;
        $customer_collection->rider_id = $order->rider_id;
        $customer_collection->city_id = $order->city_id;
        $customer_collection->township_id = $order->township_id;
        $customer_collection->address = $order->full_address;
        $customer_collection->schedule_date = Carbon::now();
        $customer_collection->save();
        return $customer_collection;
    }
    
    public function updateCustomerCollectionByRider($data, $reuploadPhoto)
    {
        $customer_collection =  CustomerCollection::where('id',$data['id'])->first();
        $customer_collection->paid_amount  = $data['amount'];
        $customer_collection->note   = $data['reason'];
        $customer_collection->status   = 'complete';
        $customer_collection->complete_at   = Carbon::now();
        $file_name = null;
        if ($reuploadPhoto) {
            $photo = $data['photo'];
            $file_name = $this->uploadFile($photo, 'public', 'customer_collection');
            $imageUrl = asset('/storage/customer_collection/' . $file_name);
            $customer_collection->item_image = $file_name;
        }
        $customer_collection->save();
        return $customer_collection;
    }
}
