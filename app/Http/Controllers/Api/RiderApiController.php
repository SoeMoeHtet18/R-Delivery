<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiderCreateApiRequest;
use App\Http\Requests\RiderLoginApiRequest;
use App\Http\Requests\RiderUpdateApiRequest;
use App\Models\Rider;
use App\Repositories\OrderRepository;
use App\Repositories\RiderRepository;
use App\Repositories\TownshipRepository;
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
    protected $townshipRepository;

    public function __construct(RiderRepository $riderRepository, RiderService $riderService, OrderRepository $orderRepository, OrderService $orderService, TownshipRepository $townshipRepository)
    {
        $this->riderRepository = $riderRepository;
        $this->riderService = $riderService;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->townshipRepository = $townshipRepository;
    }

    public function riderLoginApi(RiderLoginApiRequest $request)
    {
        if (Auth::guard('rider')->attempt(['phone_number' => request('phone_number'), 'password' => request('password')])) {
            $rider = Rider::where('id', Auth::guard('rider')->user()->id)->first();
            $rider->token =  $rider->createToken('rider')->accessToken;
            $rider->refresh_token =  $rider->createToken('rider')->accessToken;
            Rider::where('phone_number', request('phone_number'))->update(['token' => $rider->token, 'refresh_token' => $rider->refresh_token]);
            return response()->json(['data' => $rider, 'message' => 'Successfully Logged In', 'status' => 'success'], 200);
        } else {
            return response()->json(['data' => [], 'message' => 'Invalid credentials.', 'status' => 'fail'], 401);
        }
    }

    public function show()
    {
        $rider = auth()->guard('rider-api')->user();
        return response()->json(['data' => $rider, 'message' => 'Successfully Get Rider Detail', 'status' => 'success'], 200);
    }

    public function getOrderList(Request $request)
    {
        $rider = auth()->guard('rider-api')->user();
        $data = $request->all();
        $orders = $this->riderRepository->getOrderList($data, $rider->id);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get Order List By Rider ID', 'status' => 'success'], 200);
    }

    public function getShopListByRiderID()
    {
        $rider = auth()->guard('rider-api')->user();
        $shops = $this->riderRepository->getShopListByRiderID($rider->id);
        return response()->json(['data' => $shops, 'message' => 'Successfully Get Shop List By Rider ID', 'status' => 'success'], 200);
    }

    public function create(RiderCreateApiRequest $request)
    {
        $data = $request->all();
        $rider = $this->riderService->saveRiderData($data);
        $rider->token =  $rider->createToken('rider')->accessToken;
        $rider->refresh_token =  $rider->createToken('rider')->accessToken;
        Rider::where('id', $rider->id)->update(['token' => $rider->token, 'refresh_token' => $rider->refresh_token]);

        return response()->json(['data' => $rider, 'message' => 'Successfully Create Rider', 'status' => 'success'], 200);
    }

    public function update(RiderUpdateApiRequest $request)
    {
        $data = $request->all();
        $rider = auth()->guard('rider-api')->user();
        $data = $this->riderService->updateRiderByID($data, $rider);

        return response()->json(['data' => $data, 'message' => 'Successfully Update Rider', 'status' => 'success'], 200);
    }

    public function changeOrderStatus(Request $request)
    {
        $order_id = $request->order_id;
        $status = $request->status;
        $order = $this->orderRepository->getOrderByID($order_id);
        $data = $this->orderService->changeStatus($order, $status);
        return response()->json(['data' => $data, 'message' => 'Successfull Change Order Status', 'status' => 'success'], 200);
    }

    public function getAllRidersByTownshipID(Request $request)
    {
        $township_id = $request->township_id;
        $township = $this->townshipRepository->getTownshipById($township_id);
        $riders = $township->riders;
        return response()->json(['data' => $riders, 'message' => 'Successfull Get Riders By Township', 'status' => 'success'], 200);
    }
}
