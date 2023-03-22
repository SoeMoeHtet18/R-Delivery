<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerPaymentApiController extends Controller
{
    protected $customerPaymentService;
    public function __construct(CustomerPaymentService $customerPaymentService)
    {
        $this->customerPaymentService = $customerPaymentService;
    }

    public function insertCustomerPayment(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'amount' => 'required',
            'order_id' => 'required',
            'type'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        } else {
            $data = $request->all();
            $customer_payment = $this->customerPaymentService->saveCustomerPayment($data);

            return response()->json( ['data' => $customer_payment, 'message' => 'Successfully Create Customer Payment ', 'status' => 'success'], 200); 
        }
    }
}
