<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CollectionRepository;
use App\Services\CollectionService;
use Illuminate\Http\Request;

class CollectionApiController extends Controller
{
    protected $collectionRepository;
    protected $collectionService;

    public function __construct(CollectionRepository $collectionRepository, CollectionService $collectionService)
    {
        $this->collectionRepository = $collectionRepository;
        $this->collectionService    = $collectionService;
    }

    public function updateCollectionByRider(Request $request,$collection_id) {
        $data = $request->all();
        $collection = $this->collectionService->updateCollectionByRider($data, $collection_id);
        return response()->json(['data' => $collection, 'message' => 'Successfully Updated Collection By Rider', 'status' => 'success'], 200);
    }

    public function getCollectionById($collection_id) {
        $collection = $this->collectionRepository->getCollectionById($collection_id);
        return response()->json(['data' => $collection, 'message' => 'Successfully get Collection By ID', 'status' => 'success'], 200);
    }

    public function createCollectionByShopUser(Request $request, $id)
    {
        $data = $request->all();
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id = $shop_user->shop_id;
        $collection = $this->collectionService->createCollectionByShopUser($data, $shop_id);
        return response()->json(['data' => $collection, 'message' => 'Successfully created collection by shop user']);
    }

    public function getAllCollectionsByShopUser()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id = $shop_user->shop_id;
        $collections = $this->collectionRepository->getAllCollectionsByShopUser($shop_id);
        return response()->json(['data' => $collections, 'message' => 'Successfully get collections by shop user']);
    }
}
