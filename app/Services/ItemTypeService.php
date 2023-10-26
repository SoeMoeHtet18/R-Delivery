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
        return $itemtype;
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

    public function updateItemType($name, $id)
    {
        $item_type = ItemType::findOrFail($id);
        $item_type->name = $name;
        $item_type->save();
        return $item_type;
    }
}