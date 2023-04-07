<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerPaymentApiRequest;
use App\Http\Requests\CustomerPaymentRequest;
use App\Repositories\CustomerPaymentRepository;
use App\Services\CustomerPaymentService;
use Illuminate\Http\Request;

class CustomerPaymentApiController extends Controller
{
    protected $customerPaymentService;
    protected $customerPaymentRepository;
    public function __construct(CustomerPaymentService $customerPaymentService, CustomerPaymentRepository $customerPaymentRepository)
    {
        $this->customerPaymentService = $customerPaymentService;
        $this->customerPaymentRepository = $customerPaymentRepository;
    }

    public function insertCustomerPayment(CustomerPaymentApiRequest $request)
    {  
        $data = $request->all();
        $file = $request->file('proof_of_payment');
        $customer_payment = $this->customerPaymentService->saveCustomerPayment($data,$file);

        return response()->json( ['data' => $customer_payment, 'message' => 'Successfully Create Customer Payment ', 'status' => 'success'], 200); 
        
    }

    public function customerPaymentDetail(string $id)
    {
        $data = $this->customerPaymentRepository->getCustomerPaymentByID($id);
        return response()->json( ['data' => $data, 'message' => 'Successfully Get Customer Payment Detail', 'status' => 'success'], 200); 
    }

    public function getCustomerPaymentList()
    {
        $data = $this->customerPaymentRepository->getCustomerPaymentList();
        return response()->json( ['data' => $data, 'message' => 'Successfully Get Customer Payment List', 'status' => 'success'], 200); 
    }
}
