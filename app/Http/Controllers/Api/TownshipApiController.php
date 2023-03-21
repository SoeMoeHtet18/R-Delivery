<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TownshipRepository;
use Illuminate\Http\Request;

class TownshipApiController extends Controller
{
    protected $townshipRepository;

    public function __construct(TownshipRepository $townshipRepository)
    {
        $this->townshipRepository = $townshipRepository;
    }

    public function getAllTownshipList()
    {
        $townships = $this->townshipRepository->getAllTownships();
        return response()->json(['data' => $townships, 'message' => 'Successfully Get Townships List', 'status' => 'success', 200]);
    }

    public function getAllTownshipListByCityID(Request $request)
    {
        $city_id = $request->city_id;
        $townships = $this->townshipRepository->getAllTownshipsByCityID($city_id) ;
        return response()->json(['data' => $townships, 'message' => 'Successfully Get Townships List', 'status' => 'success', 200]);
    }
}
