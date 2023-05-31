<?php

namespace App\Services;

use App\Models\Township;

class TownshipService
{
    public function saveTownshipData($data)
    {
        $township = new Township();
        $township->name = $data['name'];
        $township->city_id = $data['city'];
        $township->delivery_fees = $data['delivery_fees'];
        $township->save();
    }

    public function updateTownshipByID($data,$township)
    {
        $township->name = $data['name'];
        $township->city_id = $data['city'];
        $township->delivery_fees = $data['delivery_fees'];
        $township->save();
    }

    public function deleteTownshipByID($id)
    {
        Township::destroy($id);
    }
}