<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\PaymentTypeRepository;
use Illuminate\Http\Request;

class PaymentTypeApiController extends Controller
{
    protected $paymentTypeRepository;

    public function __construct(PaymentTypeRepository $paymentTypeRepository)
    {
        $this->paymentTypeRepository = $paymentTypeRepository;
    }

    public function getAllPaymentType()
    {
        $payment_type = $this->paymentTypeRepository->getPaymentTypeList();
        return response()->json(['data'=> $payment_type, 'message' => 'Successfully Get Payment Type List', 'status' => 'success'],200);
    }
}
