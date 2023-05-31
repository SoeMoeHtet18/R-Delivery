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

    public function getAllTownshipsQuery()
    {
        $query = Township::leftJoin('cities','cities.id','townships.city_id')->select('townships.*','cities.name as city_name');
        return $query;
    }

    public function getAllTownshipsByCityID($id)
    {
        $townships = Township::where('city_id', $id)->orderBy('name','asc')->get();
        return $townships;
    }

    public function getAllTownshipsByCityIDQuery($id)
    {
        $query = Township::where('city_id', $id);
        return $query;
    }

    public function getAllTownshipsCount()
    {
        $townshipcount = Township::count();
        return $townshipcount;
    }

    public function getDeliveryFees($township_id)
    {
        $township = Township::where('id',$township_id)->first();
        $delivery_fees = $township->delivery_fees;
        return $delivery_fees;
    }
}