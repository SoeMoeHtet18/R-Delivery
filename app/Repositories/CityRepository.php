<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository
{   
    public function getAllCitiesQuery()
    {
        $query = City::select('*');
        return $query;
    }

    public function getAllCities()
    {
        $cities = City::orderBy('name','asc')->get();
        return $cities;
    }
    public function getByCityID($id)
    {
        $city = City::findOrFail($id);
        return $city;
    }

    public function getAllCityCount()
    {
        $citycount = City::count();
        return $citycount;
    }
}