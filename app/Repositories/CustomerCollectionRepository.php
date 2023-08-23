<?php

namespace App\Repositories;

use App\Models\CustomerCollection;

class CustomerCollectionRepository
{
    public function getCustomerCollectionById($id)
    {
        return CustomerCollection::with([
            'city','township','rider','shop','collection_group','order'
            ])->findOrFail($id);
    }

    public function getAllCustomerCollectionsQueryForTable($data)
    {
        $search = $data['search'];
        $shop = $data['shop'];
        $rider = $data['rider'];
        $status = $data['status'];
        $branch_id = auth()->user()->branch_id;

        $customer_collections = CustomerCollection::leftJoin('orders', 'customer_collections.order_id', '=', 'orders.id')
            ->leftJoin('riders', 'customer_collections.rider_id', '=', 'riders.id')
            ->leftJoin('shops', 'customer_collections.shop_id', '=', 'shops.id')
            ->leftJoin('cities', 'customer_collections.city_id', '=', 'cities.id')
            ->leftJoin('townships', 'customer_collections.township_id', '=', 'townships.id')
            ->leftJoin('collection_groups', 'customer_collections.collection_group_id', '=', 'collection_groups.id')
            ->where(function ($query) use ($search) {
                $query->where('orders.order_code', 'like', '%' . $search . '%')
                    ->orWhere('riders.name', 'like', '%' . $search . '%')
                    ->orWhere('shops.name', 'like', '%' . $search . '%')
                    ->orWhere('collection_groups.collection_group_code', 'like', '%' . $search . '%')
                    ->orWhere('customer_collections.customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_collections.customer_phone_number', 'like', '%' . $search . '%')
                    ->orWhere('customer_collections.customer_collection_code', 'like', '%' . $search . '%')
                    ->orWhere('customer_collections.collection_group_id', 'like', '%' . $search . '%');
            })
            ->where('customer_collections.branch_id', $branch_id)
            ->select(
                'customer_collections.*',
                'orders.order_code as order_code',
                'riders.name as rider_name',
                'shops.name as shop_name',
                'collection_groups.collection_group_code as collection_group_code',
                'cities.name as city_name',
                'townships.name as township_name'
            );

        if ($shop) {
            $customer_collections = $customer_collections->where('customer_collections.shop_id', $shop);
        }

        if ($rider) {
            $customer_collections = $customer_collections->where('customer_collections.rider_id', $rider);
        }

        if ($status) {
            $customer_collections = $customer_collections->where('customer_collections.status', $status);
        }


        // $customer_collections = $customer_collections->orderBy('id', 'desc')->get();

        // $customer_collections = $customer_collections->map(function ($customer_collection) {
        //     $customer_collection['order_code'] = $customer_collection->order->order_code;
        //     $customer_collection['customer_name'] = $customer_collection->order->customer_name;
        //     $customer_collection['shop_name'] = $customer_collection->order->shop->name;
        //     $customer_collection['collection_group'] = $customer_collection->collection_group->id ?? null;
        //     return $customer_collection;
        // });

        return $customer_collections;
    }

    public function getWarehouseCustomerCollections($data)
    {
        $search = $data['search'];
        $shop = $data['shop'];
        $rider = $data['rider'];
        $branch_id = auth()->user()->branch_id;

        $customer_collections = CustomerCollection::leftJoin('orders', 'customer_collections.order_id', 'orders.id')
            ->leftJoin('riders', 'customer_collections.rider_id', 'riders.id')
            ->leftJoin('shops', 'customer_collections.shop_id', 'shops.id')
            ->leftJoin('collection_groups', 'customer_collections.collection_group_id', 'collection_groups.id')
            ->where('customer_collections.status', 'in-warehouse')
            ->where(function ($query) use ($search) {
                $query->where('orders.order_code', 'like', '%' . $search . '%')
                    ->orWhere('riders.name', 'like', '%' . $search . '%')
                    ->orWhere('shops.name', 'like', '%' . $search . '%')
                    ->orWhere('collection_groups.collection_group_code', 'like', '%' . $search . '%')
                    ->orWhere('customer_collections.customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_collections.customer_phone_number', 'like', '%' . $search . '%')
                    ->orWhere('customer_collections.customer_collection_code', 'like', '%' . $search . '%')
                    ->orWhere('customer_collections.collection_group_id', 'like', '%' . $search . '%');
            })
            ->where('customer_collections.branch_id', $branch_id)
            ->select(
                'customer_collections.*',
                'orders.order_code as order_code',
                'riders.name as rider_name',
                'shops.name as shop_name',
                'collection_groups.collection_group_code as collection_group_code'
            );

        if ($shop) {
            $customer_collections = $customer_collections->where('customer_collections.shop_id', $shop);
        }

        if ($rider) {
            $customer_collections = $customer_collections->where('customer_collections.rider_id', $rider);
        }

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
        $customer_collections = CustomerCollection::leftJoin('orders', 'customer_collections.order_id', '=', 'orders.id')
            ->leftJoin('shops', 'customer_collections.shop_id', '=', 'shops.id')
            ->where('customer_collections.collection_group_id', $id)
            ->select(
                'customer_collections.*',
                'orders.order_code as order_code',
                'shops.name as shop_name',
            );
        return $customer_collections;
    }

    public function getAllCustomerCollections()
    {
        $customer_collections = CustomerCollection::all();
        return $customer_collections;
    }

    public function getCustomerCollectionByShopID($shop_id)
    {
        $customer_collections = CustomerCollection::leftJoin('orders', 'customer_collections.order_id', '=', 'orders.id')
            ->leftJoin('riders', 'customer_collections.rider_id', '=', 'riders.id')
            ->leftJoin('shops', 'customer_collections.shop_id', '=', 'shops.id')
            ->leftJoin('cities', 'customer_collections.city_id', '=', 'cities.id')
            ->leftJoin('townships', 'customer_collections.township_id', '=', 'townships.id')
            ->leftJoin('collection_groups', 'customer_collections.collection_group_id', '=', 'collection_groups.id')
            ->where('customer_collections.shop_id', $shop_id)
            ->select(
                'customer_collections.*',
                'orders.order_code as order_code',
                'riders.name as rider_name',
                'shops.name as shop_name',
                'collection_groups.collection_group_code as collection_group_code',
                'cities.name as city_name',
                'townships.name as township_name'
            );

        return $customer_collections;
    }
}