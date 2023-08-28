<?php

namespace App\Repositories;

use App\Models\CollectionGroup;
use Illuminate\Support\Facades\DB;

class CollectionGroupRepository
{
    public function getCollectionGroupByID($id) 
    {
        $collectionGroup = CollectionGroup::findOrFail($id);
        return $collectionGroup;
    }

    public function getAllCollectionGroups()
    {
        $collectionGroups = CollectionGroup::all();
        return $collectionGroups;
    }

    public function getAllCollectionGroupData()
    {
        $branch_id = auth()->user()->branch_id;
        $collectionGroups = CollectionGroup::where('collection_groups.branch_id', $branch_id)
            ->with('collections', 'customer_collections')
            ->leftJoin('riders', 'riders.id', '=', 'collection_groups.rider_id')
            ->select('collection_groups.*', 'riders.name as rider_name')
            ->selectRaw("(SELECT SUM(collections.total_quantity) FROM collections WHERE
                    collections.collection_group_id = collection_groups.id) as total_quantity")
            ->withCount([
                'collections as total_collection_quantity',
                'customer_collections as total_customer_collection_quantity'
            ]);
                
        return $collectionGroups;
    }

    public function getAllCollectionGroupsQuery()
    {
        $collectionGroups = CollectionGroup::leftJoin('riders', 'riders.id', 'collection_groups.rider_id')
            ->select('collection_groups.*', 'riders.name as rider_name');
        foreach($collectionGroups as $collectionGroup) {
            $collections = $collectionGroup->collections;
            if($collections) {
                $shops = [];
                foreach($collections as $collection) {
                    $shops[] = $collection->shop->name;
                }
            }
            $collectionGroup->shops = $shops;
        }
        return $collectionGroups;
    }

    public function getAllCollectionGroupCount()
    {
        $user = auth()->user();
        return CollectionGroup::where('branch_id', $user->branch_id)->count();
    }
}