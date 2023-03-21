<?php

namespace App\Repositories;

use App\Models\Township;

class TownshipRepository
{
    public function getTownshipById($id)
    {
        $township = Township::with('city')->findOrFail($id); 
        return $township;
    }

    public function getAllTownships()
    {
        $townships = Township::all();
        return $townships;
    }

    public function getAllTownshipsByCityID($id)
    {
        $townships = Township::where('city_id', $id)->orderBy('id','DESC')->get();
        return $townships;
    }
}