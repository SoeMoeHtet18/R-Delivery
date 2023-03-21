<?php

namespace App\Services;

use App\Models\Order;
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
        $rider->save();
        return $rider;
    }

    public function deleteRiderByID($id)
    {
        Rider::destroy($id);
    }
}