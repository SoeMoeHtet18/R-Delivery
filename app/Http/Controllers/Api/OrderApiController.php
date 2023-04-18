<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getDataByCustomerPhoneNumber(Request $request)
    {
        $phone = $request->phone_number;
        $data = $this->orderRepository->getOrderByCustomerPhoneNumber($phone);

        if($data != null) {
            return response()->json(['data'=>$data, 'status'=>'success', 'message'=>'Successfully get order'],200);
        }
        else {
            return response()->json(['data'=>null, 'status'=>'fail', 'message'=>'Fail to get order'],200);
        }
    }

    public function getOrderCountByShopID()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id   = $shop_user->shop_id;
        $count = $this->orderRepository->getOrdersStatusCountByShopID($shop_id);
        return response()->json(['data'=> $count, 'message' => 'Successfully Get Orders Count By Shop ID', 'status' => 'success'],200);
    }
    
    public function getOrderTotalAmountByShopID()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id   = $shop_user->shop_id;
        $total_amount = $this->orderRepository->getOrdersTotalAmountByShopID($shop_id);
        return response()->json(['data'=> $total_amount, 'message' => 'Successfully Get Orders Total Amount By Shop ID', 'status' => 'success'],200);
    }

    public function getOrderCountByRiderID()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $count = $this->orderRepository->getOrdersStatusCountByRiderID($rider_id);
        return response()->json(['data'=> $count, 'message' => 'Successfully Get Orders Count By Rider ID', 'status' => 'success'],200);
    }

    public function getOrderTotalAmountByRiderID()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $total_amount = $this->orderRepository->getOrdersTotalAmountByRiderID($rider_id);
        return response()->json(['data'=> $total_amount, 'message' => 'Successfully Get Orders Total Amount By Rider ID', 'status' => 'success'],200);
    }
}
