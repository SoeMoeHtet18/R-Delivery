<?php

namespace App\Services;

use App\Models\Branch;

class BranchService
{
    public function saveBranchData($data)
    {
        $branch = new Branch();
        $branch->name = $data['name'];
        $branch->city_id = $data['city_id'];
        $branch->phone_number = $data['phone_number'];
        $branch->address = $data['address'];
        $branch->save();
    }

    public function updateBranchByID($data, $branch)
    {
        $branch->name = $data['name'];
        $branch->city_id = $data['city_id'];
        $branch->phone_number = $data['phone_number'];
        $branch->address = $data['address'];
        $branch->save();
    }

    public function deleteBranchByID($id)
    {
        Branch::destroy($id);
    }
}
