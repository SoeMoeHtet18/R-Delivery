<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ItemTypeRepository;
use Illuminate\Http\Request;

class ItemTypeApiController extends Controller
{
    protected $itemTypeRepository;

    public function __construct(ItemTypeRepository $itemTypeRepository)
    {
        $this->itemTypeRepository = $itemTypeRepository;
    }

    public function getAllItemType()
    {
        $item_type = $this->itemTypeRepository->getAllItemTypes();
        return response()->json(['data'=> $item_type, 'message' => 'Successfully Get Item Type List', 'status' => 'success'],200);
    }
}
