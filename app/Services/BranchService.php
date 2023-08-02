<?php

namespace App\Services;

use App\Models\Branch;

class BranchService
{
    public function saveBranchData($data)
    {
        $branch = new Branch();
        $branch->name = $data['name'];
        $branch->save();
    }

    public function updateBranchByID($data, $branch)
    {
        $branch->name = $data['name'];
        $branch->save();
    }

    public function deleteRiderPaymentByID($id)
    {
        Branch::destroy($id);
    }
}
