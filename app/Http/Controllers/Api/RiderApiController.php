<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use App\Repositories\OrderRepository;
use App\Repositories\RiderRepository;
use App\Services\OrderService;
use App\Services\RiderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RiderApiController extends Controller
{
    protected $riderRepository;
    protected $riderService;
    protected $orderRepository;
    protected $orderService;

    public function __construct(RiderRepository $riderRepository,RiderService $riderService, OrderRepository $orderRepository, OrderService $orderService)
    {
        $this->riderRepository = $riderRepository;
        $this->riderService = $riderService;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }
    public function riderLoginApi(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        }

        
        if(Auth::guard('rider')->attempt(['phone_number' => request('phone_number'), 'password' => request('password')])){ 
            $rider = Rider::where('id', Auth::guard('rider')->user()->id)->first();
            $rider->token =  $rider->createToken('rider')->accessToken;
            $rider->refresh_token =  $rider->createToken('rider')->accessToken;
            Rider::where('phone_number',request('phone_number'))->update(['token' => $rider->token,'refresh_token' => $rider->refresh_token]);       
            return response()->json( ['data' => $rider, 'message' => 'Successfully Logged In', 'status' => 'success'], 200); 
        }else{
            return response()->json(['data' => [], 'message' => 'Invalid credentials.', 'status' => 'fail'], 401); 
        }
    }

    public function show($id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        return response()->json(['data'=> $rider, 'message'=> 'Successfully Get Rider Detail', 'status' => 'success', 200]);
    }

    public function getOrderListByRiderID($id)
    {
        $orders = $this->riderRepository->getOrderHistoryListByRiderID($id);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get Order List By Rider ID', 'status' => 'success', 200]);
    }

    public function getOrderList(Request $request)
    {
        $data = $request->all();
        $orders = $this->riderRepository->getPendingOrderListForAuthenticatedRider($data);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get Order List By Rider ID', 'status' => 'success', 200]);
    }

    public function getShopListByRiderID($id)
    {
        $shops = $this->riderRepository->getShopListByRiderID($id);
        return response()->json(['data' => $shops, 'message' => 'Successfully Get Shop List By Rider ID', 'status' => 'success', 200]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'                  => 'required|string',
            'phone_number'          => 'required|string|unique:riders',
            'email'                 => 'unique:riders',
            'password'              => 'required|min:8',
            'device_id'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        } else {
            $data = $request->all();
            $rider = $this->riderService->saveRiderData($data);
            $rider->token =  $rider->createToken('rider')->accessToken;
            $rider->refresh_token =  $rider->createToken('rider')->accessToken;
            Rider::where('id',$rider->id)->update(['token' => $rider->token,'refresh_token' => $rider->refresh_token]);       

            return response()->json( ['data' => $rider, 'message' => 'Successfully Create Rider', 'status' => 'success'], 200); 
        }
    }
    public function update(Request $request,string $id)
    {
        $validator = Validator::make($request->all(),[
            'name'                  => 'required|string',
            'phone_number'          => 'required|string|unique:riders,phone_number,' . $id,
            'email'                 => 'unique:riders,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        } else {
            $data = $request->all();
            $rider = $this->riderRepository->getRiderByID($id);  
            $data = $this->riderService->updateRiderByID($data, $rider);  

            return response()->json( ['data' => $data, 'message' => 'Successfully Update Rider', 'status' => 'success'], 200); 
        }
    }

    public function changeOrderStatus(Request $request)
    {
        $order_id = $request->order_id;
        $status = $request->status;
        $order = $this->orderRepository->getOrderByID($order_id);
        $this->orderService->changeStatus($order,$status);
        return response()->json(['data' => [], 'message' => 'Successfull Change Order Status', 'status' => 'success',200]);
    }
}
