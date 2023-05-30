<?php

namespace App\Repositories;

use App\Models\ItemType;

class ItemTypeRepository
{
    public function getByItemTypeID($id)
    {
        $itemtype = ItemType::findOrFail($id);
        return $itemtype;
    }

    public function getAllItemTypes()
    {
        $item_types = ItemType::orderBy('name','asc')->get();
        return $item_types;
    }

    public function getAllItemTypesQuery()
    {
        $query = ItemType::select('*');
        return $query;
    }

    public function getAllItemTypesCount()
    {
        $itemTypeCount = ItemType::count();
        return $itemTypeCount;
    }
}