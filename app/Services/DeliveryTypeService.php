<?php

namespace App\Services;

use App\Models\DeliveryType;

class DeliveryTypeService
{
    public function saveDeliveryTypes($data)
    {
        // dd($data);
        $deliveryType = new DeliveryType();
        $deliveryType->name = $data['name'];
        $deliveryType->notified_on = $data['notified_on'];
        $deliveryType->save();
    }

    public function updateCityByID($data, $deliveryType)
    {
        // dd($data);
        $deliveryType->name = $data['name'];
        $deliveryType->notified_on = $data['notified_on'];
        $deliveryType->save();
    }

    public function deleteDeliveryTypeByID($id)
    {
        DeliveryType::destroy($id);
    }

}