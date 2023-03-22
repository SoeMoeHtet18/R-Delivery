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
        $shops = City::all();
        return $shops;
    }
    public function getByCityID($id)
    {
        $city = City::findOrFail($id);
        return $city;
    }
}