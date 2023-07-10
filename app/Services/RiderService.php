<?php

namespace App\Services;

use App\Models\Rider;
use App\Models\Township;
use Illuminate\Support\Facades\Hash;

class RiderService
{
    public function saveRiderData($data)
    {
        $rider = new Rider();
        $rider->name = $data['name'];
        $rider->phone_number = $data['phone_number'];
        $rider->email = $data['email'] ?? null;
        $rider->password = bcrypt($data['password']);
        $rider->salary_type = $data['salary_type'];
        $rider->device_id = $data['device_id'] ?? null;
        $rider->save();
        if(isset($data['township_id'])) {
            $townships = [];
            $township_ids = $data['township_id'];
            foreach($township_ids as $township_id) {
                $township = Township::find($township_id);
                $townships[$township_id] = [
                    'rider_fees' => $township->delivery_fees,
                ];
            }        
            $rider->townships()->sync($townships);
        }
        return $rider;
    }

    public function updateRiderByID($data, $rider)
    {
        $rider->name = $data['name'];
        $rider->phone_number = $data['phone_number'];
        $rider->email = $data['email'] ?? $rider->email;
        if(isset($data['password'])) {
            $rider->password =  bcrypt($data['password']);
        }
        $rider->device_id = $data['device_id'] ?? $rider->device_id;
        $rider->salary_type = $data['salary_type'];
        
        if(isset($data['township_id'])) {
            $townships = [];
            $township_ids = $data['township_id'];
            foreach($township_ids as $township_id) {
                $township = Township::find($township_id);
                $townships[$township_id] = [
                    'rider_fees' => $township->delivery_fees,
                ];
            }          
            $rider->townships()->sync($townships);
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
        $rider->townships()->sync($townships);
    }

    public function changePassword($rider, $old_password, $new_password) {
        $password = $rider->password;
        if (Hash::check($old_password, $password)) {
            $rider->password = bcrypt($new_password);
            $rider->save();
            return true;
        } else {
           return false;
        }
    }
}