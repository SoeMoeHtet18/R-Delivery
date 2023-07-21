<?php

namespace App\Services;

use App\Models\Collection;

class CollectionService
{
    public function updateCollectionByRider($data, $collection_id)
    {
        $collection                     = Collection::where('id', $collection_id)->first();
        $collection->total_quantity     = $data['total_quantity'];
        $collection->total_amount       = $data['total_amount'];
        $collection->paid_amount        = $data['paid_amount'];
        $collection->collected_at       = $data['collected_at'];
        // $collection->status             = $data['status'];
        $collection->note             = $data['note'];
        $collection->save();
        return $collection;
    }

    public function createCollectionByShopUser($data, $shop_id)
    {
        $collection = new Collection();
        $collection->total_quantity     = $data['total_quantity'];
        $collection->total_amount       = $data['total_amount'];
        $collection->assigned_at        = $data['assigned_at'];
        $collection->shop_id            = $shop_id;
        $collection->status             = $data['status'];
        $collection->note               = $data['note'];
        $collection->is_payable         = 0;
        $collection->save();
        return $collection;
    }

    public function saveCollectionData($data)
    {
        $collection = new Collection();
        $collection->total_quantity =  $data['total_quantity'];
        $collection->total_amount =  $data['total_amount'];
        $collection->paid_amount =  $data['paid_amount'];
        $collection->rider_id =  $data['rider_id'] ?? null;
        $collection->shop_id =  $data['shop_id'];
        $collection->assigned_at =  $data['assigned_at'];
        $collection->collected_at =  $data['collected_at'];
        $collection->note =  $data['note'];
        $collection->status =  $data['status'];
        $collection->is_payable =  false;
        $collection->save();
        return $collection;
    }

    public function updateCollectionByID($data, $collection)
    {
        $collection->total_quantity =  $data['total_quantity'];
        $collection->total_amount =  $data['total_amount'];
        $collection->paid_amount =  $data['paid_amount'];
        $collection->rider_id =  $data['rider_id'] ?? null;
        $collection->shop_id =  $data['shop_id'];
        $collection->assigned_at =  $data['assigned_at'];
        $collection->collected_at =  $data['collected_at'];
        $collection->note =  $data['note'];
        $collection->status =  $data['status'];
        $collection->is_payable =  false;
        $collection->save();
        return $collection;
    }

    public function deleteCollectionByID($id)
    {
        Collection::destroy($id);
    }
}
