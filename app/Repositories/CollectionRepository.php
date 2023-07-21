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
    
    public function getCollectionsByRiderId($rider_id, $page) 
    {
        $limit = 10; 
        $offset = ($page - 1) * $limit;
        $collections = Collection::with('shop')->where('rider_id',$rider_id)->offset($offset)->limit($limit)->orderBy('id','DESC')->get();
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
}