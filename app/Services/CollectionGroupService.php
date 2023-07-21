<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\CollectionGroup;
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

        $collections = [];
        if(isset($data['collection_id'])) {
            $collectionIds = $data['collection_id'];
            foreach ($collectionIds as $collectionId) {
                $collection = Collection::where('id', $collectionId)->update(['collection_group_id' => $collectionGroup->id]);
            }    
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

        $shopIds = $data['shop_id'];
        $existingCollectionIds = $collectionGroup->collections->pluck('id')->toArray();

        foreach ($shopIds as $shopId) {
            $collection = Collection::firstOrNew([
                'collection_group_id' => $collectionGroup->id,
                'shop_id' => $shopId
            ]);

            if (in_array($collection->id, $existingCollectionIds)) {
                continue;
            }

            $collection->save();
        }
        return $collectionGroup;
    }

    public function deleteCollectionGroupByID($id)
    {
        CollectionGroup::destroy($id);
    }
}
