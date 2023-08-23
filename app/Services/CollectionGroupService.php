<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\CustomerCollection;
use Carbon\Carbon;

class CollectionGroupService
{
    public function saveCollectionGroupByAdmin($data)
    {
        $user = auth()->user();

        $collectionGroup = CollectionGroup::create([
            'total_amount' => $data['total_amount'],
            'rider_id' => $data['rider_id'],
            'assigned_date' => $data['assigned_date'] ?? Carbon::tomorrow(),
            'branch_id' => $user->branch_id
        ]);

        $collections = [];
        if (isset($data['shop_id'])) {
            $shopIds = $data['shop_id'];
            foreach ($shopIds as $shopId) {
                $collections[] = [
                    'collection_group_id' => $collectionGroup->id,
                    'shop_id' => $shopId
                ];
            }
        }
        Collection::insert($collections);
        return $collectionGroup;
    }

    public function saveCollectionGroup($data)
    {
        $user = auth()->user();

        $collectionGroup = CollectionGroup::create([
            'collection_group_code' => $data['collection_group_code'],
            'total_amount' => $data['total_amount'] ?? 0,
            'rider_id' => $data['rider_id'],
            'assigned_date' => $data['assigned_date'],
            'branch_id' => $user->branch_id,
            'total_quantity' => $data['total_quantity'] ?? 0,
            'total_collection' => $data['total_collection']
        ]);

        if (isset($data['checkedShopCollections'])) {
            $shopCollections = $data['checkedShopCollections'];
            Collection::whereIn('id', $shopCollections)
                ->update(['collection_group_id' => $collectionGroup->id,'rider_id' => $data['rider_id']]);
            
        }
        if (isset($data['checkedCustomerCollections'])) {
            $shopCollections = $data['checkedCustomerCollections'];
            CustomerCollection::whereIn('id', $shopCollections)
                ->update(['collection_group_id' => $collectionGroup->id,'rider_id' => $data['rider_id'],
                'schedule_date' => $data['assigned_date']]);
        }
        return $collectionGroup;
    }

    public function updateCollectionGroupByAdmin($collectionGroup, $data)
    {
        $collectionIds = [];
        $customerCollectionIds = [];
        
        if (isset($data['collection_id'])) {
            $collectionIds = $data['collection_id'];
        
            Collection::whereIn('id', $collectionIds)
                ->update(['collection_group_id' => $collectionGroup->id]);
        }

        if (isset($data['customer_collection_id'])) {
            $customerCollectionIds = $data['customer_collection_id'];
        
            CustomerCollection::whereIn('id', $customerCollectionIds)
                ->update(['collection_group_id' => $collectionGroup->id]);
        }
        
        $totalCollection = count($collectionIds) + count($customerCollectionIds);

        $collectionGroup->update([
            'total_collection' => $totalCollection,
            'total_quantity' => $data['total_quantity'],
            'total_amount' => $data['total_amount'],
            'rider_id' => $data['rider_id'],
            'assigned_date' => $data['assigned_date']
        ]);

      
        return $collectionGroup;
    }

    public function deleteCollectionGroupByID($id)
    {
        CollectionGroup::destroy($id);
    }
}
