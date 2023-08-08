<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\ThirdPartyVendor;

class ThirdPartyVendorService
{
    public function saveThirdPartyVendorData($data)
    {
        $thirdPartyVendor = new ThirdPartyVendor();
        $thirdPartyVendor->name = $data['name'];
        $thirdPartyVendor->type = $data['type'];
        $thirdPartyVendor->address = $data['address'];
        $thirdPartyVendor->save();
    }

    public function updateThirdPartyVendorByID($data, $thirdPartyVendor)
    {
        $thirdPartyVendor->name = $data['name'];
        $thirdPartyVendor->name = $data['name'];
        $thirdPartyVendor->type = $data['type'];
        $thirdPartyVendor->address = $data['address'];
        $thirdPartyVendor->save();
    }

    public function deleteThirdPartyVendorByID($id)
    {
        ThirdPartyVendor::destroy($id);
    }
}
