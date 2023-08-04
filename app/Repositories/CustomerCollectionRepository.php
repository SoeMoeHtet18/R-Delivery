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

    public function getAllCustomerCollections($data)
    {
        $search = $data['search'];
        $shop = $data['shop'];
        $status = $data['status'];

        $customer_collections = CustomerCollection::with(['order.rider', 'order.shop']);

        if ($search) {
            $customer_collections = $customer_collections
                ->whereHas('order', function ($q) use ($search) {
                    $q->where('order_code', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%')
                        ->orWhereHas('shop', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                })
                ->orWhere('customer_collection_code', 'like', '%' . $search . '%')
                ->orWhere('collection_group_id', 'like', '%' . $search . '%');
        }
        if ($shop) {
            $customer_collections = $customer_collections
                ->whereHas('order', function ($q) use ($shop) {
                    $q->where('shop_id', $shop);
                });
        }

        if ($status) {
            $customer_collections = $customer_collections->where('status', $status);
        }


        $customer_collections = $customer_collections->orderBy('id', 'desc')->get();

        $customer_collections = $customer_collections->map(function ($customer_collection) {
            $customer_collection['order_code'] = $customer_collection->order->order_code;
            $customer_collection['customer_name'] = $customer_collection->order->customer_name;
            $customer_collection['shop_name'] = $customer_collection->order->shop->name;
            $customer_collection['collection_group'] = $customer_collection->collection_group->id ?? null;
            return $customer_collection;
        });

        return $customer_collections;
    }

    public function getWarehouseCustomerCollections($data)
    {
        $search = $data['search'];
        $shop = $data['shop'];

        $customer_collections = CustomerCollection::where('status','in-warehouse')->with(['order.rider', 'order.shop']);

        if ($search) {
            $customer_collections = $customer_collections
                ->whereHas('order', function ($q) use ($search) {
                    $q->where('order_code', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%')
                        ->orWhereHas('shop', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                })
                ->orWhere('customer_collection_code', 'like', '%' . $search . '%')
                ->orWhere('collection_group_id', 'like', '%' . $search . '%');
        }
        if ($shop) {
            $customer_collections = $customer_collections
                ->whereHas('order', function ($q) use ($shop) {
                    $q->where('shop_id', $shop);
                });
        }

        $customer_collections = $customer_collections->orderBy('id', 'desc')->get();

        $customer_collections = $customer_collections->map(function ($customer_collection) {
            $customer_collection['order_code'] = $customer_collection->order->order_code;
            $customer_collection['customer_name'] = $customer_collection->order->customer_name;
            $customer_collection['shop_name'] = $customer_collection->order->shop->name;
            $customer_collection['collection_group'] = $customer_collection->collection_group->id ?? null;
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
    
    public function getAllCustomerCollectionsByGroupId($id) 
    {
        $customer_collections = CustomerCollection::where('collection_group_id',$id)->get();
        return $customer_collections;
    }
}
