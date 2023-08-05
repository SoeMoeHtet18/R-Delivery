<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\CustomerCollectionRepository;
use Illuminate\Http\Request;

class CustomerCollectionApiController extends Controller
{
    protected $customerCollectionRepository;

    public function __construct(CustomerCollectionRepository $customerCollectionRepository)
    {
        $this->customerCollectionRepository = $customerCollectionRepository;
    }
    public function getCustomerCollectionCode(Request $request)
    {
        $shop_id = $request->shop_id;
        $customer_collection_code = Helper::nomenclature('customer_collections', 'CE', 'id', $shop_id, 'S');
        return response()->json(['data' => $customer_collection_code,  'status' => 'success', 'message' => 'Successfully get customer collection code'], 200);
    }

    public function show($id)
    {
        $customer_collection = $this->customerCollectionRepository->show($id);
        return response()->json(['data' => $customer_collection,  'status' => 'success', 'message' => 'Successfully get customer collection detail'], 200);

    }
    
    public function changeCustomerCollectionCode(Request $request)
    {
        $shop_id = $request->shop_id;
        $customer_collection_code = Helper::nomenclature('customer_collections', 'CE', 'id', $shop_id, 'S', 'change');
        return response()->json(['data' => $customer_collection_code,  'status' => 'success', 'message' => 'Successfully get customer collection code'], 200);
    }
}
