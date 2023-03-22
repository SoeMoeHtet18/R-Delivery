<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository
{   
    public function getAllCitiesQuery()
    {
        $cities = City::select('*');
        return $cities;
    }

    public function getAllCities()
    {
        $shops = City::all();
        return $shops;
    }
    public function getByCityID($id)
    {
        $city = City::findOrFail($id);
        return $city;
    }
}