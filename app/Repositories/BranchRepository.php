<?php

namespace App\Repositories;

use App\Models\Branch;

class BranchRepository
{
    public function getAllBranchQuery()
    {
        $query = Branch::select('branches.*');
        return $query;
    }

    public function show($id)
    {
        $data = Branch::findOrFail($id);
        return $data;
    }
    
}