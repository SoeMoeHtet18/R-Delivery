<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopPaymentRequest;
use App\Services\ShopPaymentService;

class ShopPaymentApiController extends Controller
{
    protected $shopPaymentService;
    public function __construct(ShopPaymentService $shopPaymentService)
    {
        $this->shopPaymentService = $shopPaymentService;
    }

    public function insertShopPayment(ShopPaymentRequest $request)
    {
            $data = $request->all();
            $shop_payment = $this->shopPaymentService->saveShopPaymentData($data);

            return response()->json( ['data' => $shop_payment, 'message' => 'Successfully Create Shop Payment ', 'status' => 'success'], 200); 
    }
}
