<?php

namespace App\Repositories;

use App\Models\Branch;

class BranchRepository
{
    public function getAllBranchQuery()
    {
        $query = Branch::with('townships')->leftJoin('cities', 'cities.id','branches.city_id')->select('branches.*','cities.name as city_name');
        return $query;
    }

    public function show($id)
    {
        $data = Branch::findOrFail($id);
        return $data;
    }
    
    public function getAllData()
    {
        $data = Branch::orderBy('id','desc')->get();
        return $data;
    }
}