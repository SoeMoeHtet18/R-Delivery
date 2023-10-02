<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentDetailRequest;
use App\Repositories\PaymentDetailRepository;
use App\Services\PaymentDetailService;
use Illuminate\Http\Request;

class PaymentDetailApiController extends Controller
{
    protected $paymentDetailRepository;
    protected $paymentDetailService;

    public function __construct(
        PaymentDetailRepository $paymentDetailRepository,
        PaymentDetailService $paymentDetailService
    )
    {
        $this->paymentDetailRepository = $paymentDetailRepository;
        $this->paymentDetailService = $paymentDetailService;
    }

    public function create(PaymentDetailRequest $request)
    {
        $data = $request->all();
        $paymentDetail = $this->paymentDetailService->createPaymentDetail($data);
        return response()->json(['data' => $paymentDetail,
            'message' => 'Successfully Created Payment Detail', 'status' => 'success'], 200);
    }

    public function update(PaymentDetailRequest $request, $paymentDetail)
    {
        $data = $request->all();
        $updatedPaymentDetail = $this->paymentDetailService->updatePaymentDetail($data, $paymentDetail);
        return response()->json(['data' => $updatedPaymentDetail,
            'message' => 'Successfully Updated Payment Detail', 'status' => 'success'], 200);
    }

    public function getPaymentDetailByShop()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $paymentDetail = $this->paymentDetailRepository->getPaymentDetailByShop($shop_user->shop_id);
        return response()->json(['data' => $paymentDetail,
            'message' => 'Successfully Get Payment Detail', 'status' => 'success'], 200);
    }
}
