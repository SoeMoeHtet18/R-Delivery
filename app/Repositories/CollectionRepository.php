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

    public function getAllCollectionsByShopUser($shop_id)
    {
        $collections = Collection::where('shop_id', $shop_id)->get();
        return $collections;
    }

    public function getAllCollectionsQueryForShop($shop_id)
    {
        $collections = Collection::where('shop_id', $shop_id);
        return $collections;
    }
}