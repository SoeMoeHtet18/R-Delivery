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
}