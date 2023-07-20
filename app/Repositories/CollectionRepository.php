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
}