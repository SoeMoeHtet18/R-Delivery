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
        return Branch::orderBy('name','asc')->get();
    }

    public function getAllBranchCount()
    {
        return Branch::count();
    }

    public function getAllBranchData($request, $search)
    {
        $township_id = $request->township_id;
        $city_id = $request->city_id;
        return Branch::with(['townships', 'city', 'riders'])
                ->withCount('riders')
                ->when(isset($search), function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })->when(isset($city_id), function ($query) use ($city_id) {
                    $query->where('city_id', $city_id);
                })->when(isset($township_id), function ($query) use ($township_id) {
                    $query->whereHas('townships', function($query) use ($township_id) {
                        $query->where('id',$township_id);
                    });
                })
                ->orderBy('id','desc')
                ->get()
                ->map(function ($branch) {
                    $branch->township_name = $branch->townships->pluck('name')->implode(', ');
                    return $branch;
                });
    }

    public function getBranchById($id)
    {
        $branch = Branch::with(['townships', 'city', 'riders'])
                    ->withCount('riders')->findOrFail($id);
        $branch['township_names'] =  $branch->townships->pluck('name')->implode(', ');
        $branch['rider_names'] =  $branch->riders->pluck('name')->implode(', ');
        return $branch;
    }
}