<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
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

    public function updateCollectionByRider(Request $request) {
        $data = $request->all();
        $collection_id = $request->collection_id;
        $collection = $this->collectionService->updateCollectionByRider($data, $collection_id);
        return response()->json(['data' => $collection, 'message' => 'Successfully Updated Collection By Rider', 'status' => 'success'], 200);
    }

    public function getCollectionById($collection_id) {
        $collection = $this->collectionRepository->getCollectionById($collection_id);
        return response()->json(['data' => $collection, 'message' => 'Successfully get Collection By ID', 'status' => 'success'], 200);
    }

    public function getCollectionsByRiderId($page = 1) {
        $rider_id = auth()->guard('rider-api')->user()->id;
        // dd($rider_id);
        $collections = $this->collectionRepository->getCollectionsByRiderId($rider_id, $page);
        return response()->json(['data' => $collections, 'message' => 'Successfully Get Collection list By Rider ID', 'status' => 'success'], 200);
    }
    
    public function createCollectionByShopUser(Request $request)
    {
        $data = $request->all();
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id = $shop_user->shop_id;
        $collection = $this->collectionService->createCollectionByShopUser($data, $shop_id);
        return response()->json(['data' => $collection, 'message' => 'Successfully created collection by shop user', 'status' => 'success'], 200);
    }

    public function getAllCollectionsByShopUser()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id = $shop_user->shop_id;
        $collections = $this->collectionRepository->getAllCollectionsByShopUser($shop_id);
        return response()->json(['data' => $collections, 'message' => 'Successfully get collections by shop user', 'status' => 'success'], 200);
    }

    public function getCollectionsByShop($page = 1,Request $request) {
        $shop_id = $request->shop_id;
        $collection_group_id = $request->collection_group_id;
        // dd($rider_id);
        $collections = $this->collectionRepository->getCollectionsByShop($shop_id,$collection_group_id, $page);
        return response()->json(['data' => $collections, 'message' => 'Successfully Get Collection list By Shop', 'status' => 'success'], 200);
    }

    public function getCollectionCode(Request $request) 
    {
        $shop_id = $request['shop_id'];
        $collection_code = Helper::nomenclature('collections', 'P', 'id', $shop_id, 'S');
        return response()->json(['data' => $collection_code,  'status' => 'success', 'message' => 'Successfully get collection code'], 200);
    }
}
