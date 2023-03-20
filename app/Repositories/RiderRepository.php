<?php

namespace App\Repositories;

use App\Models\Rider;

class RiderRepository
{
    public function getAllRidersByDESC()
    {
        $riders = Rider::select('*')->orderBy('id','DESC');
        return $riders;
    }

    public function getRiderByID($id)
    {
        $rider = Rider::findOrFail($id);
        return $rider;
    }
}