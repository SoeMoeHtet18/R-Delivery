<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\Shop;

class CollectionService
{
    public function updateCollectionByRider($data, $collection_id)
    {
        $collection                     = Collection::where('id', $collection_id)->first();
        $collection->total_quantity     = $data['total_quantity'];
        $collection->total_amount       = $data['total_amount'];
        $collection->paid_amount        = $data['paid_amount'];
        $collection->collected_at       = $data['collected_at'];
        $collection->status             = 'complete';
        $collection->note             = $data['note'];
        $collection->save();
        return $collection;
    }

    public function createCollectionByShopUser($data, $shop_id)
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $collection_code = Helper::nomenclature('collections', 'P', 'id', $shop_id, 'S');

        $collection = new Collection();
        $collection->collection_code    = $collection_code;
        $collection->total_quantity     = $data['total_quantity'];
        $collection->total_amount       = $data['total_amount'];
        $collection->assigned_at        = $data['assigned_at'];
        $collection->shop_id            = $shop_id;
        $collection->status             = $data['status'];
        $collection->note               = $data['note'];
        $collection->is_payable         = 0;
        $collection->branch_id         = $shop_user->branch_id;
        $collection->save();
        return $collection;
    }

    public function saveCollectionData($data)
    {
        $user = auth()->user();
        $collection = new Collection();
        $collection->collection_code =  $data['collection_code'];
        $collection->total_quantity =  $data['total_quantity'];
        $collection->total_amount =  $data['total_amount'];
        $collection->paid_amount =  $data['paid_amount'];
        $collection->rider_id =  $data['rider_id'] ?? null;
        $collection->shop_id =  $data['shop_id'];
        $collection->assigned_at =  $data['assigned_at'] ?? null;
        $collection->collected_at =  $data['collected_at'] ?? null;
        $collection->note =  $data['note'];
        $collection->status =  'pending';
        $collection->is_payable =  false;
        $collection->branch_id =  $user->branch_id;
        $collection->save();
        return $collection;
    }

    public function updateCollectionByID($data, $collection)
    {
        $collection_group = CollectionGroup::where('id', $data['collection_group_id'])->first();
        $rider_id = $collection_group->rider_id; 
        $collection->total_quantity =  $data['total_quantity'];
        $collection->total_amount =  $data['total_amount'];
        $collection->paid_amount =  $data['paid_amount'];
        $collection->rider_id =  $rider_id ?? $collection->rider_id;
        $collection->collection_group_id =  $data['collection_group_id'] ?? null;
        $collection->shop_id =  $data['shop_id'];
        $collection->assigned_at =  $data['assigned_at'] ?? null;
        $collection->collected_at =  $data['collected_at'] ?? null;
        $collection->note =  $data['note'];
        $collection->status =  $data['status'];
        $collection->is_payable =  $data['is_payable'] ?? $collection->is_payable;
        $collection->save();
        return $collection;
    }

    public function deleteCollectionByID($id)
    {
        Collection::destroy($id);
    }

    public function saveCollectionFromGroup($data, $rider_id = null, $collection_group_id = null) 
    {        
        $shop_id = $data['shop_id'];
        $collection_code = Helper::nomenclature('collections', 'P', 'id', $shop_id, 'S');
        $user = auth()->user();
        $total_amount = empty($data['total_amount']) ? null : $data['total_amount'];

        $description = empty($data['description']) ? null : $data['description'];

        $collection = new Collection();
        $collection->collection_code =  $collection_code;
        $collection->total_quantity =  $data['total_quantity'] ?? null;
        $collection->total_amount =  $total_amount;
        $collection->paid_amount =  $data['paid_amount'] ?? null;
        $collection->collection_group_id =  $collection_group_id;
        $collection->rider_id =  $rider_id;
        $collection->shop_id =  $data['shop_id'];
        $collection->assigned_at =  $data['assigned_at'] ?? null;
        $collection->collected_at =  $data['collected_at'] ?? null;
        $collection->note =  $description;
        $collection->status =  'pending';
        $collection->is_payable =  false;
        $collection->branch_id =  $user->branch_id;
        $collection->save();
        return $collection;

    }
}
