<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Services\CityService;
use Illuminate\Http\Request;

class CityApiController extends Controller
{
    protected $cityRepository;
    protected $cityService;

    public function __construct(CityRepository $cityRepository, CityService $cityService)
    {
        $this->cityRepository = $cityRepository;
        $this->cityService = $cityService;
    }

    public function getAllCityList()
    {
        $cities = $this->cityRepository->getAllCities();
        return response()->json(['data'=> $cities, 'message' => 'Successfully Get City List', 'status' => 'success'],200);
    }

    public function getCityTableData(Request $request)
    {
        $search = $request->search;
        $data = $this->cityRepository->getAllCityData($search);
        return response()->json([
            'data' => $data,
            'message' => 'Successfully get city table data',
            'status' => 'success'
        ], 200);
    }

    public function storeCityData(Request $request) {
        $data = $request->data;
        $city = $this->cityService->saveCityData($data);
        return response()->json([
            'data'   => $city,
            'message'=> 'Successfully save city',
            'status' => 'success'
        ], 200);
    }
    
    public function updateCityData(Request $request) {
        $data = $request->data;
        $data['name'] = $data['changedCityName'];
        $city = $this->cityRepository->getByCityID($data['id']);
        $updateCity = $this->cityService->updateCityByID($data, $city);
        return response()->json([
            'data'   => $updateCity,
            'message'=> 'Successfully update city',
            'status' => 'success'
        ], 200);
    }
}
