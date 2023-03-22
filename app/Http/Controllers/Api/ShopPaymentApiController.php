<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShopPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopPaymentApiController extends Controller
{
    protected $shopPaymentService;
    public function __construct(ShopPaymentService $shopPaymentService)
    {
        $this->shopPaymentService = $shopPaymentService;
    }

    public function insertShopPayment(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'shop_id'  => 'required',
            'amount'    => 'required',
            'type'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        } else {
            $data = $request->all();
            $shop_payment = $this->shopPaymentService->saveShopPaymentData($data);

            return response()->json( ['data' => $shop_payment, 'message' => 'Successfully Create Shop Payment ', 'status' => 'success'], 200); 
        }
    }
}
