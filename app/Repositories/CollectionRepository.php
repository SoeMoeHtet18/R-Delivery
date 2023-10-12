<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\CustomerCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CollectionRepository
{
    public function getCollectionById($id)
    {
        $collection = Collection::with('shop')->where('id',$id)->first();
        $collection['shop_name'] = $collection->shop->name;
        $collection['shop_phone_number'] = $collection->shop->phone_number;
        return $collection;
    }
    
    public function getCollectionsByRiderId($rider_id, $page)
    {
        $limit = 10; 
        $offset = ($page - 1) * $limit;
        $todayDate = Carbon::now()->format('Y-m-d');
        $collections = Collection::with(['shop','collection_group'])->whereHas('collection_group',function($query) use($rider_id,$todayDate) {
            $query->where('rider_id',$rider_id)->where('assigned_date',$todayDate);
        })->where('rider_id',$rider_id)->offset($offset)->limit($limit)->orderBy('id','DESC')->get();
        foreach($collections as $collection) {
            $collection['shop_name'] = $collection->shop->name;
            $collection['shop_phone_number'] = $collection->shop->phone_number;
            $shop_id = $collection->shop_id;
            $collectionGroupId = $collection->collection_group_id;
            //count customer collection by shop id
            $customerCollectionCount = CustomerCollection::with(['order', 'collection_group'])
                ->where(['collection_group_id' => $collectionGroupId, 'shop_id' => $shop_id])->count();
            $collection['customer_collection_count'] = $customerCollectionCount;
        }
        return $collections;
    }

    public function getAllCollectionsByShopUser($shop_id)
    {
        $collections = Collection::where('shop_id', $shop_id)
            ->whereNotNull('total_amount')
            ->leftJoin('riders', 'riders.id', 'collections.rider_id')
            ->select('collections.*', 'riders.name as rider_name')
            ->orderBy('id','desc')
            ->get();
        return $collections;
    }

    public function getAllCollectionsQueryForShop($shop_id)
    {
        $collections = Collection::where('shop_id', $shop_id);
        return $collections;
    }

    public function getAllCollections()
    {
        $collections = Collection::get();
        return $collections;
    }

    public function getAllCollectionByCollectionGroupId($id)
    {
        $collections = Collection::where('collection_group_id', $id)->get();
        return $collections;
    }

    public function getAllCollectionsWithoutRider()
    {
        $collections = Collection::whereNull('rider_id')->get();
        return $collections;
    }

    public function getAllCollectionsQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = Collection::leftJoin('riders', 'riders.id', 'collections.rider_id')
            ->leftJoin('shops', 'shops.id', 'collections.shop_id')
            ->leftJoin('collection_groups', 'collection_groups.id', 'collections.collection_group_id')
            ->where('collections.branch_id', $branch_id)
            ->select('collections.*', 'riders.name as rider_name', 'shops.name as shop_name', 'collection_groups.collection_group_code as collection_group_code');
        return $query;
    }

    public function getCollectionsByID($id)
    {
        $deliveryType = Collection::findOrFail($id);
        return $deliveryType;
    }

    public function getCollectionsQueryByShopID($id) 
    {
        $query = Collection::where('collections.shop_id',$id)
            ->whereNotNull('collections.total_amount')
            ->leftJoin('collection_groups', 'collection_groups.id', 'collections.collection_group_id')
            ->leftJoin('riders', 'riders.id', 'collections.rider_id')
            ->select('collections.*', 'collection_groups.collection_group_code as collection_group_code',
                'riders.name as rider_name');
        return $query;
    }

    public function getCollectionsQueryByRiderID($id)
    {
        $query = Collection::where('collections.rider_id',$id)
            ->whereNotNull('collections.total_amount')
            ->leftJoin('collection_groups', 'collection_groups.id', 'collections.collection_group_id')
            ->leftJoin('shops', 'shops.id', 'collections.shop_id')
            ->select('collections.*', 'collection_groups.collection_group_code as collection_group_code',
                'shops.name as shop_name');
        return $query;
    }

    public function getPaidAmountByShopUser($shop_id)
    {
        $paid_credit = Collection::where('shop_id', $shop_id)->sum('paid_amount');

        return $paid_credit;
    }  
    
    public function getPaymentHistoryForShop($shop_id)
    {
        $payment_histories = Collection::where('shop_id', $shop_id)
            ->whereNotNull('paid_amount')
            ->orderByDesc('created_at')
            ->leftJoin('riders', 'riders.id', 'collections.rider_id')
            ->select('collections.paid_amount','collections.created_at', 'riders.name as rider_name')
            ->get();
        foreach($payment_histories as $payment_history) {
            $payment_history->type = 'rider';
        }
        return $payment_histories;
    }

    public function getCollectionsByShop($shop_id, $collection_group_id, $page) 
    {
        $limit = 10; 
        $offset = ($page - 1) * $limit;
        $customerCollections = CustomerCollection::with(['order.shop', 'collection_group'])->whereHas('order',function($q) use ($shop_id){
                $q->where('shop_id',$shop_id);
            })->where('collection_group_id',$collection_group_id)->offset($offset)->limit($limit)->orderBy('id','DESC')->get();
        foreach($customerCollections as $customerCollection){
            $customerCollection['customer_name'] =  $customerCollection->order->customer_name;
            $customerCollection['total_amount'] = $customerCollection->order->total_amount;
            $customerCollection['order_id'] = $customerCollection->order->order_code;
            $customerCollection['shop_name'] = $customerCollection->order->shop->name;
        }
            
        return $customerCollections;
    }

    public function getCollectionByIDWithData($id)
    {
        $collection = Collection::where('id',$id)->with('collection_group','rider','shop')->firstOrFail();
        return $collection;
    }

    public function getCollectionsQueryByGroupID($id)
    {
        $collections = Collection::where('collection_group_id', $id)
            ->leftJoin('shops', 'shops.id', 'collections.shop_id')
            ->select('collections.*', 'shops.name as shop_name');
        return $collections;
    }

    public function getAssignedCollectionByRiderForToday($rider_id)
    {
        $today = Carbon::today();
        $collections = Collection::where('rider_id', $rider_id)
            ->whereHas('collection_group', function($query) use ($today) {
                $query->whereDate('assigned_date', $today);
            })
            ->leftJoin('shops', 'shops.id', 'collections.shop_id')
            ->leftJoin('riders', 'riders.id', 'collections.rider_id')
            ->select('shops.name as shop_name', 'riders.name as rider_name',
                     DB::raw('MAX(collected_at) as latest_collected_at'), 
                     DB::raw('SUM(total_amount) as total_amount'), 
                     DB::raw('SUM(paid_amount) as paid_amount'), 
                     DB::raw('SUM(total_quantity) as total_quantity'))
            ->groupBy('shop_id', 'shops.name', 'riders.name')
            ->get();
        return $collections;
    }

    public function getAllCollectionCount()
    {
        $user = auth()->user();
        return Collection::where('branch_id', $user->branch_id)->count();
    }

    public function getShopPickUpsByShopID($shop_id)
    {
        return Collection::where('shop_id', $shop_id)
            ->with(['collection_group', 'shop', 'rider'])
            ->get();
    }
}