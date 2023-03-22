<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerPaymentRequest;
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

    public function insertCustomerPayment(CustomerPaymentRequest $request)
    {  
        $data = $request->all();
        $customer_payment = $this->customerPaymentService->saveCustomerPayment($data);

        return response()->json( ['data' => $customer_payment, 'message' => 'Successfully Create Customer Payment ', 'status' => 'success'], 200); 
        
    }
}
