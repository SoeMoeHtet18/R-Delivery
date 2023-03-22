<?php

namespace App\Services;

use App\Models\City;

class CityService
{
    public function saveCityData($data)
    {
        $city = new City();
        $city->name = $data['name'];
        $city->save();
    }

    public function updateCityByID($data,$city)
    {
        $city->name = $data['name'];
        $city->save();
    }

    public function deleteCityByID($id)
    {
        City::destroy($id);
    }
}