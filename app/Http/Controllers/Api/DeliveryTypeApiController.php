<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\DeliveryTypesRepository;

class DeliveryTypeApiController extends Controller
{
    protected $deliveryTypeRepository;

    public function __construct(DeliveryTypesRepository $deliveryTypeRepository)
    {
        $this->deliveryTypeRepository = $deliveryTypeRepository;
    }

    public function getAllDeliveryTypeList()
    {
        $deliveryTypes = $this->deliveryTypeRepository->getAllDeliveryTypes();
        return response()->json(['data'=> $deliveryTypes, 'message' => 'Successfully Get Delivery Type List', 'status' => 'success'],200);
    }
}
