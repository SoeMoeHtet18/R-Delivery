<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\CollectionGroup;

class CollectionRepository
{   
    public function getCollectionById($id) 
    {
        $collection = Collection::where('id',$id)->first();
        return $collection;
    }
    
    public function getCollectionsByRiderId($rider_id) 
    {
        $collections = Collection::with('shop')->where('rider_id',$rider_id)->get();
        foreach($collections as $collection) {
            $collection['shop_name'] = $collection->shop->name;
            $collection['shop_phone_number'] = $collection->shop->phone_number;
        }
        return $collections;
    }

    public function getAllCollectionsByShopUser($shop_id)
    {
        $collections = Collection::where('shop_id', $shop_id)
            ->whereNotNull('total_quantity')
            ->leftJoin('riders', 'riders.id', 'collections.rider_id')
            ->select('collections.*', 'riders.name as rider_name')
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
        $query = Collection::select('*');
        return $query;
    }

    public function getCollectionsByID($id)
    {
        $deliveryType = Collection::findOrFail($id);
        return $deliveryType;
    }

    public function getCollectionsQueryByShopID($id) 
    {
        $query = Collection::where('shop_id',$id);
        return $query;
    }

    public function getCollectionsQueryByRiderID($id)
    {
        $query = Collection::where('rider_id',$id);
        return $query;
    }

}