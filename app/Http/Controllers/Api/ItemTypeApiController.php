<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ItemTypeRepository;
use App\Services\ItemTypeService;
use Illuminate\Http\Request;

class ItemTypeApiController extends Controller
{
    protected $itemTypeRepository;
    protected $itemTypeService;

    public function __construct(ItemTypeRepository $itemTypeRepository, ItemTypeService $itemTypeService)
    {
        $this->itemTypeRepository = $itemTypeRepository;
        $this->itemTypeService = $itemTypeService;
    }

    public function getAllItemType()
    {
        $item_type = $this->itemTypeRepository->getAllItemTypes();
        return response()->json(['data'=> $item_type, 'message' => 'Successfully Get Item Type List', 'status' => 'success'],200);
    }

    public function getItemTypes(Request $request)
    {
        $search = $request->search;
        $item_types = $this->itemTypeRepository->getItemTypes($search);
        return response()->json([
            'data' => $item_types,
            'message' => 'Successfully Get Item Types',
            'status' => 'success'
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $item_type = $this->itemTypeService->saveItemTypeData($data);
        return response()->json([
            'data' => $item_type,
            'message' => 'Successfully created item type',
            'status' => 'success'
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $name = $request->name;
        $this->itemTypeService->updateItemType($name, $id);
        return response()->json([
            'message' => 'Successfully updated item type',
            'status' => 'success'
        ], 200);
    }

    public function delete(string $id)
    {
        $this->itemTypeService->deleteItemTypeByID($id);
        return response()->json([
            'message' => 'Successfully deleted item type',
            'status' => 'success'
        ], 200);
    }
}
