<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use Illuminate\Http\Request;

class CityApiController extends Controller
{
    protected $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function getAllCityList()
    {
        $cities = $this->cityRepository->getAllCities();
        return response()->json(['data'=> $cities, 'message' => 'Successfully Get Cities List', 'status' => 'success', 200]);
    }
}