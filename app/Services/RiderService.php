<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Gate;
use App\Models\Rider;
use App\Models\ThirdPartyVendor;
use App\Models\Township;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RiderService
{
    public function saveRiderData($data)
    {
        $user = auth()->user();

        $rider = new Rider();
        $rider->name = $data['name'];
        $rider->phone_number = $data['phone_number'];
        $rider->email = $data['email'] ?? null;
        $rider->password = bcrypt($data['password']);
        $rider->salary_type = $data['salary_type'];
        $rider->device_id = $data['device_id'] ?? null;
        $rider->branch_id = $user->branch_id;
        $rider->save();
        
        $this->assignTownship($rider, $data);
        return $rider;
    }

    public function updateRiderByID($data, $rider)
    {
        $rider->name = $data['name'];
        $rider->phone_number = $data['phone_number'];
        $rider->email = $data['email'] ?? $rider->email;
        if (isset($data['password'])) {
            $rider->password =  bcrypt($data['password']);
        }
        $rider->device_id = $data['device_id'] ?? $rider->device_id;
        if (isset($data['salary_type'])) {
            $rider->salary_type = $data['salary_type'];
        }
        $rider->save();
        return $rider;
    }

    public function deleteRiderByID($id)
    {
        Rider::destroy($id);
    }

    public function assignTownship($rider, $data)
    {
        $townships = $data['township_id'];
        $rider_fees = $data['rider_fees'];
        
        // $rider->townships()->sync([]);
        
        // Insert the records into the pivot table with rider_fees values
        $records = [];
        for ($i = 0; $i < count($townships); $i++) {
            $records[] = [
                'rider_id' => $rider->id,
                'township_id' => $townships[$i],
                'rider_fees' => $rider_fees[$i],
            ];
        }
        
        DB::table('rider_township')->insert($records);        
    }

    public function changePassword($rider, $old_password, $new_password)
    {
        $password = $rider->password;
        if (Hash::check($old_password, $password)) {
            $rider->password = bcrypt($new_password);
            $rider->save();
            return true;
        } else {
            return false;
        }
    }

    public function assignBranch($rider, $data)
    {
        $branchIds = $data['branch_id'];
        $rider_fees = $data['rider_fees'];

        foreach ($branchIds as $i => $branchId) {
            $branch = Branch::with('townships')->find($branchId);
            if ($branch) {
                $townships = $branch->townships;
                foreach ($townships as $township) {
                    $existingData = [
                        'rider_id' => $rider->id,
                        'township_id' => $township->id,
                    ];
                    $newData = [
                        'rider_id' => $rider->id,
                        'township_id' => $township->id,
                        'rider_fees' => $rider_fees[$i] ?? 0,
                    ];
                    
                    DB::table('rider_township')->updateOrInsert($existingData, $newData);
                }
            }
        }
    }
    
    public function assignGate($rider, $data)
    {
        $gateIds = $data['gate_id'];
        $rider_fees = $data['rider_fees'];

        $records = [];
        foreach ($gateIds as $i => $gateId) {
            $gate = Gate::with('townships')->find($gateId);
            if ($gate) {
                $townships = $gate->townships;
                foreach ($townships as $township) {
                    $existingData = [
                        'rider_id' => $rider->id,
                        'township_id' => $township->id,
                    ];
                    $newData = [
                        'rider_id' => $rider->id,
                        'township_id' => $township->id,
                        'rider_fees' => $rider_fees[$i],
                    ];
                    
                    DB::table('rider_township')->updateOrInsert($existingData, $newData);
                }
            }
        }
    }
    
    public function assignThirdPartyVendor($rider, $data)
    {
        $thirdPartyVendorIds = $data['third_party_vendor_id'];
        $rider_fees = $data['rider_fees'];

        $records = [];
        foreach ($thirdPartyVendorIds as $i => $thirdPartyVendorId) {
            $thirdPartyVendor = ThirdPartyVendor::with('townships')->find($thirdPartyVendorId);
            if ($thirdPartyVendor) {
                $townships = $thirdPartyVendor->townships;
                foreach ($townships as $township) {
                    $existingData = [
                        'rider_id' => $rider->id,
                        'township_id' => $township->id,
                    ];
                    $newData = [
                        'rider_id' => $rider->id,
                        'township_id' => $township->id,
                        'rider_fees' => $rider_fees[$i],
                    ];
                    
                    DB::table('rider_township')->updateOrInsert($existingData, $newData);
                }
            }
        }
    }
}
