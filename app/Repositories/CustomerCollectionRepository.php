<?php

namespace App\Repositories;

use App\Models\CustomerCollection;

class CustomerCollectionRepository
{
    public function getCustomerCollectionById($id)
    {
        $customer_collection = CustomerCollection::findOrFail($id);
        return $customer_collection;
    }

    public function getAllCustomerCollectionsQuery()
    {
        $customer_collections = CustomerCollection::with(['order.rider', 'order.shop'])->get();

        $customer_collections = $customer_collections->map(function ($customer_collection) {
            $customer_collection['order_code'] = $customer_collection->order->order_code;
            $customer_collection['customer_name'] = $customer_collection->order->customer_name;
            $customer_collection['shop_name'] = $customer_collection->order->shop->name;
            return $customer_collection;
        });
        
        return $customer_collections;
        
    }

    public function show($id) 
    {
        $customer_collection = CustomerCollection::with(['order.rider', 'order.shop'])->first();
        $customer_collection['order_code'] = $customer_collection->order->order_code;
        $customer_collection['customer_name'] = $customer_collection->order->customer_name;
        $customer_collection['shop_name'] = $customer_collection->order->shop->name;
        return $customer_collection;
    }
}