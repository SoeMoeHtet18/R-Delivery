<?php

namespace App\Repositories;

use App\Models\DeliveryType;

class DeliveryTypesRepository
{   
    public function getAllDeliveryTypesQuery()
    {
        $query = DeliveryType::select('*');
        return $query;
    }

    public function getAllDeliveryTypes()
    {
        $deliveryTypes = DeliveryType::orderBy('name','desc')->get();
        return $deliveryTypes;
    }

    public function getDeliveryTypesByID($id)
    {
        $deliveryType = DeliveryType::findOrFail($id);
        return $deliveryType;
    }

    public function getByDeliveryTypeID($id)
    {
        $deliveryType = DeliveryType::findOrFail($id);
        return $deliveryType;
    }

}