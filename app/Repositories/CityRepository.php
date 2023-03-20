<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository
{
    public function getByCityID($id)
    {
        $city = City::findOrFail($id);
        return $city;
    }
}