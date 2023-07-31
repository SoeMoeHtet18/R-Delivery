<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\CustomerCollection;
use Carbon\Carbon;

class CollectionGroupService
{
    public function saveCollectionGroupByAdmin($data)
    {
        $collectionGroup = CollectionGroup::create([
            'total_amount' => $data['total_amount'],
            'rider_id' => $data['rider_id'],
            'assigned_date' => $data['assigned_date'] ?? Carbon::tomorrow()
        ]);

        $collections = [];
        if(isset($data['shop_id'])) {
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
        $collectionGroup = CollectionGroup::create([
            'total_amount' => $data['total_amount'],
            'rider_id' => $data['rider_id'],
            'assigned_date' => $data['assigned_date']
        ]);

        if(isset($data['checkedShopCollections'])) {
            $shopCollections = $data['checkedShopCollections'];
            Collection::whereIn('id', $shopCollections)->update(['collection_group_id' => $collectionGroup->id]);
            
        }
        if(isset($data['checkedCustomerCollections'])) {
            $shopCollections = $data['checkedCustomerCollections'];
            CustomerCollection::whereIn('id', $shopCollections)->update(['collection_group_id' => $collectionGroup->id]);
            
        }
        return $collectionGroup;
    }

    public function updateCollectionGroupByAdmin($collectionGroup, $data)
    {
        $collectionGroup->fill([
            'total_amount' => $data['total_amount'],
            'rider_id' => $data['rider_id'],
            'assigned_date' => $data['assigned_date']
        ]);
        $collectionGroup->save();

        $collections = [];
        if(isset($data['collection_id'])) {
            Collection::where('collection_group_id',$collectionGroup->id)->update(['collection_group_id' => null]);
            $collectionIds = $data['collection_id'];
            foreach ($collectionIds as $collectionId) {
                $collection = Collection::where('id', $collectionId)->update(['collection_group_id' => $collectionGroup->id]);
            }    
        }
        return $collectionGroup;
    }

    public function deleteCollectionGroupByID($id)
    {
        CollectionGroup::destroy($id);
    }
}
