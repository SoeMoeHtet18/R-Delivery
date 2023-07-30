<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Traits\FileUploadTrait;
use App\Models\CustomerCollection;
use App\Models\Order;
use App\Models\Shop;

class CustomerCollectionService
{
    use  FileUploadTrait;
    public function saveCustomerCollectionByRider($rider,$data)
    {
        $customer_collection = new CustomerCollection();
        $customer_collection->order_id = $data['order_id'];
        $shop_id = Order::where('id',$data['order_id'])->first()->shop_id;
        $customer_collection_code = Helper::nomenclature('customer_collections', 'CC', 'id', $shop_id);
        $customer_collection->customer_collection_code  = $customer_collection_code;
        $customer_collection->paid_amount  = $data['amount'];
        $customer_collection->note   = $data['reason'];
        $customer_collection->status   = 'pending';
        $file_name = null;
        if ($data['photo']) {
            $photo = $data['photo'];
            $file_name = $this->uploadFile($photo, 'public', 'customer_collection');
            $imageUrl = asset('/storage/customer_collection/' . $file_name);
        }
        $customer_collection->item_image = $file_name;
        $customer_collection->save();
        return $customer_collection;
    }
}
