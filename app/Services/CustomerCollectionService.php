<?php

namespace App\Services;

use App\Models\CustomerCollection;

class CustomerCollectionService
{
   public function createCustomerCollection($data)
   {
      $customer_collection = new CustomerCollection();
      $customer_collection->customer_collection_code = $data['customer_collection_code'];
      $customer_collection->order_id = $data['order_id'];
      $customer_collection->items = $data['items'] ?? null;
      $customer_collection->paid_amount = $data['paid_amount'];
      $customer_collection->is_way_fees_payable = $data['is_way_fees_payable'];
      $customer_collection->status = 'pending';
      $customer_collection->note = $data['note'] ?? null;
      $customer_collection->save();
   }

   public function updateCustomerCollection($data, $id)
   {
      $customer_collection = CustomerCollection::where('id', $id)->first();
      $customer_collection->customer_collection_code = $customer_collection->customer_collection_code;
      $customer_collection->order_id = $customer_collection->order_id;
      $customer_collection->items = $data['items'];
      $customer_collection->paid_amount = $data['paid_amount'];
      $customer_collection->is_way_fees_payable = $data['is_way_fees_payable'];
      $customer_collection->status = $data['status'];
      $customer_collection->note = $data['note'];
      $customer_collection->save();
   }

   public function deleteCustomerCollectionByID($id)
   {
      CustomerCollection::destroy($id);
   }
}
