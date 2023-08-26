<?php

namespace App\Repositories;

use App\Models\CollectionGroup;

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
        $collectionGroups = CollectionGroup::select('collection_groups.*', 'riders.name as rider_name')
                ->leftJoin('riders', 'riders.id', 'collection_groups.rider_id')
                ->where('collection_groups.branch_id', $branch_id);
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