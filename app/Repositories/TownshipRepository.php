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
}