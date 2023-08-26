<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Models\ThirdPartyVendor;

class ThirdPartyVendorRepository
{
    public function getAllThirdPartyVendorQuery()
    {
        $query = ThirdPartyVendor::with(['townships'])->select('third_party_vendors.*');
        return $query;
    }

    public function show($id)
    {
        $data = ThirdPartyVendor::findOrFail($id);
        return $data;
    }
    
    public function getAllData()
    {
        return ThirdPartyVendor::orderBy('name','asc')->get();
    }

    public function getAllThirdPartyVendorCount()
    {
        return ThirdPartyVendor::count();
    }
}