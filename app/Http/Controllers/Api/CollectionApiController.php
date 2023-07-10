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

    public function getCollectionsByRiderId() {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $collections = $this->collectionRepository->getCollectionsByRiderId($rider_id);
        return response()->json(['data' => $collections, 'message' => 'Successfully Get Collection list By Rider ID', 'status' => 'success'], 200);
    }
}
