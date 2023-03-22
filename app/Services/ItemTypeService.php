<?php

namespace App\Services;

use App\Models\ItemType;

class ItemTypeService
{
    public function saveItemTypeData($data)
    {
        $itemtype = new ItemType();
        $itemtype->name = $data['name'];
        $itemtype->save();
    }

    public function updateItemTypeByID($data,$itemtype)
    {
        $itemtype->name = $data['name'];
        $itemtype->save();
    }

    public function deleteItemTypeByID($id)
    {
        ItemType::destroy($id);
    }
}