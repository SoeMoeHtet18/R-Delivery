<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopPaymentApiRequest;
use App\Http\Requests\ShopPaymentRequest;
use App\Repositories\ShopPaymentRepository;
use App\Repositories\ShopRepository;
use App\Services\ShopPaymentService;
use Illuminate\Http\Request;

class ShopPaymentApiController extends Controller
{
    protected $shopRepository;
    protected $shopPaymentRepository;
    protected $shopPaymentService;

    public function __construct(ShopRepository $shopRepository, ShopPaymentRepository $shopPaymentRepository, ShopPaymentService $shopPaymentService)
    {
        $this->shopRepository = $shopRepository;
        $this->shopPaymentRepository = $shopPaymentRepository;
        $this->shopPaymentService = $shopPaymentService;
    }

    public function insertShopPayment(ShopPaymentApiRequest $request)
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $data = $request->all();
        $data['shop_id'] = $shop_user->shop_id;
        $file = $request->file('image');
        $shop_payment = $this->shopPaymentService->saveShopPaymentData($data, $file);

        return response()->json(['data' => $shop_payment, 'message' => 'Successfully Create Shop Payment ', 'status' => 'success'], 200);
    }

    public function getShopPaymentListByShopID()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_payments = $this->shopPaymentRepository->getShopPaymentListByShopID($shop_user->shop_id);
        return response()->json(['data' => $shop_payments, 'message' => 'Successfully Get Shop Payments By Shop ID', 'status' => 'success'], 200);
    }

    public function getShopPaymentDetailByID($id)
    {
        $shop_payment = $this->shopPaymentRepository->getShopPaymentDetailByID($id);
        return response()->json(['data' => $shop_payment, 'message' => 'Successfully Get Shop Payment Detail By ID', 'status' => 'success'], 200);
    }
}
