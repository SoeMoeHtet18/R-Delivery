<?php

namespace App\Services;

use App\Models\Rider;

class RiderService
{
    public function saveRiderData($data)
    {
        $rider = new Rider();
        $rider->name = $data['name'];
        $rider->phone_number = $data['phone_number'];
        $rider->email = $data['email'] ?? null;
        $rider->password = bcrypt($data['password']);
        $rider->device_id = $data['device_id'] ?? null;
        $rider->save();
        if(isset($data['township_id'])) {
            $townships = $data['township_id'];
            $rider->townships()->sync($townships);
        }
        return $rider;
    }

    public function updateRiderByID($data, $rider)
    {
        $rider->name = $data['name'];
        $rider->phone_number = $data['phone_number'];
        $rider->email = $data['email'] ?? $rider->email;
        if($data['password']) {
            $rider->password =  bcrypt($data['password']);
        }
        $rider->device_id = $data['device_id'] ?? $rider->device_id;
        if(isset($data['township_id'])) {
            $townships = $data['township_id'];
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
}