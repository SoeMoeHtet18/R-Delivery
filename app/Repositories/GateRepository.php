<?php

namespace App\Repositories;

use App\Models\Gate;

class GateRepository
{
    public function show($id)
    {
        $data = Gate::with(['city','townships'])->findOrFail($id);
        return $data;
    }
    
    public function getAllGateQuery()
    {
        $query = Gate::with(['city','townships'])->leftJoin('cities', 'cities.id','gates.city_id')->select('gates.*','cities.name as city_name');
        return $query;
    }

    public function getAllData()
    {
        return Gate::with(['city','townships'])->orderBy('name','asc')->get();
    }
}