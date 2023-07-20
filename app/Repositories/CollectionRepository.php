<?php

namespace App\Repositories;

use App\Models\Collection;

class CollectionRepository
{   
    public function getCollectionById($id) 
    {
        $collection = Collection::where('id',$id)->first();
        return $collection;
    }
    
    public function getCollectionsByRiderId($rider_id) 
    {
        $collections = Collection::where('rider_id',$rider_id)->get();

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