<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    protected $orderRepository;
    protected $orderService;

    public function __construct(OrderRepository $orderRepository, OrderService $orderService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
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

    public function getOneDayOrderListByRider()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $orders = $this->orderRepository->getOneDayOrderList($rider_id);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get One Day Order List By Rider', 'status' => 'success'],200);
    }

    public function getUpcomingOrderListByRider()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $orders = $this->orderRepository->getUpcomingOrderList($rider_id);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get Upcoming Order List By Rider', 'status' => 'success'],200);
    }
    
    public function getOrderHistoryListByRider()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $orders = $this->orderRepository->getOrderHistoryList($rider_id);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get Upcoming Order List By Rider', 'status' => 'success'],200);
    }

    public function getOrderListCountByRiderID()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $orders_counts = $this->orderRepository->getOrderListCount($rider_id);
        return response()->json(['data'=>$orders_counts, 'message'=>'Successfully Get Order List Count By Rider', 'status' => 'success'], 200);
    }

    public function uploadProofOfPaymentByRider(Request $request)
    {
        $image = $request->file('image');
        $id = $request->order_id;
        $order = $this->orderRepository->getOrderByID($id);
        $uploadedImage = $this->orderService->uploadProofOfPayment($order, $image);
        $imageUrl = asset('/storage/order payment/' . $uploadedImage);
        return response()->json(['data'=>$order->id, 'message'=>'Successfully Upload Proof Of Payment By Rider', 'status' => 'success'], 200);
    }

    public function getOrderDetail(Request $request)
    {
        $order_id = $request->order_id;
        $order = $this->orderRepository->getOrderDetailWithRelatedData($order_id);
        return response()->json(['data'=>$order, 'message'=>'Successfully Get Order Detail', 'status' => 'success'], 200);
    }
}
