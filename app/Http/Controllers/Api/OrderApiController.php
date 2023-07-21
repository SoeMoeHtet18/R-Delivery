<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\CollectionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionsForShopRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    protected $orderRepository;
    protected $orderService;
    protected $collectionRepository;
    protected $transactionForShopRepository;

    public function __construct(OrderRepository $orderRepository, OrderService $orderService, CollectionRepository $collectionRepository, TransactionsForShopRepository $transactionForShopRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->collectionRepository = $collectionRepository;
        $this->transactionForShopRepository = $transactionForShopRepository;
    }

    public function getOrderCode(Request $request)
    {
        $shop_id = $request->shop_id;
        $order_code = Helper::nomenclature('orders', 'OD', 'id', $shop_id);
        return response()->json(['data' => $order_code,  'status' => 'success', 'message' => 'Successfully get order code'], 200);
    }

    public function getDataByCustomerPhoneNumber(Request $request)
    {
        $phone = $request->phone_number;
        $data = $this->orderRepository->getOrderByCustomerPhoneNumber($phone);

        if ($data != null) {
            return response()->json(['data' => $data, 'status' => 'success', 'message' => 'Successfully get order'], 200);
        } else {
            return response()->json(['data' => null, 'status' => 'fail', 'message' => 'Fail to get order'], 200);
        }
    }

    public function getOrderCountByShopID()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id   = $shop_user->shop_id;
        $count = $this->orderRepository->getOrdersStatusCountByShopID($shop_id);
        return response()->json(['data' => $count, 'message' => 'Successfully Get Orders Count By Shop ID', 'status' => 'success'], 200);
    }

    public function getOrderTotalAmountByShopID()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id   = $shop_user->shop_id;
        $total_credit = $this->orderRepository->getTotalCreditForShop($shop_id);

        $paid_credit_from_collection = $this->collectionRepository->getPaidAmountByShopUser($shop_id);
        $paid_credit_from_transaction = $this->transactionForShopRepository->getPaidAmountByShopUser($shop_id);
        $total_amount = strval($total_credit - ($paid_credit_from_collection + $paid_credit_from_transaction));
        
        return response()->json(['data' => $total_amount, 'message' => 'Successfully Get Orders Total Amount By Shop ID', 'status' => 'success'], 200);
    }

    public function getOrderCountByRiderID()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $count = $this->orderRepository->getOrdersStatusCountByRiderID($rider_id);
        return response()->json(['data' => $count, 'message' => 'Successfully Get Orders Count By Rider ID', 'status' => 'success'], 200);
    }

    public function getOrderTotalAmountByRiderID(Request $request)
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $list_status = $request->list_status;
        $total_amount = $this->orderRepository->getOrdersTotalAmountByRiderID($rider_id, $list_status);
        $total_amount['total_amount'] = $total_amount['total_amount'] == null ? '0.00' : (string)$total_amount['total_amount'];
        return response()->json(['data' => $total_amount, 'message' => 'Successfully Get Orders Total Amount By Rider ID', 'status' => 'success'], 200);
    }

    public function getOneDayOrderListByRider()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $orders = $this->orderRepository->getOneDayOrderList($rider_id);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get One Day Order List By Rider', 'status' => 'success'], 200);
    }

    public function getUpcomingOrderListByRider(Request $request,$page = 1)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $rider_id = auth()->guard('rider-api')->user()->id;
        $orders = $this->orderRepository->getUpcomingOrderList($rider_id, $start_date, $end_date, $page);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get Upcoming Order List By Rider', 'status' => 'success'], 200);
    }

    public function getOrderHistoryListByRider(Request $request,$page = 1)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $rider_id = auth()->guard('rider-api')->user()->id;
        $orders = $this->orderRepository->getOrderHistoryList($rider_id, $start_date, $end_date, $page);
        return response()->json(['data' => $orders, 'message' => 'Successfully Get Upcoming Order List By Rider', 'status' => 'success'], 200);
    }

    public function getOrderListCountByRiderID()
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $orders_counts = $this->orderRepository->getOrderListCount($rider_id);
        return response()->json(['data' => $orders_counts, 'message' => 'Successfully Get Order List Count By Rider', 'status' => 'success'], 200);
    }

    public function uploadProofOfPaymentByRider(Request $request)
    {
        $id = $request->order_id;
        $order = $this->orderRepository->getOrderByID($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $uploadedImage = $this->orderService->uploadProofOfPayment($order, $image);
            $imageUrl = asset('/storage/order payment/' . $uploadedImage);
        } else if ($request->hasImage == true) {
            $order->update(['proof_of_payment' => $order->proof_of_payment]);
        } else {
            $order->update(['proof_of_payment' => null]);
        }

        if ($order->status != 'success') {
            $status = $this->orderService->changeStatus($order, 'success');
        }

        return response()->json([
            'data' => $order->id,
            'message' => 'Successfully Upload Proof Of Payment By Rider',
            'status' => 'success'
        ], 200);
    }

    public function getOrderDetail(Request $request)
    {
        $order_id = $request->order_id;
        $order = $this->orderRepository->getOrderDetailWithRelatedData($order_id);
        return response()->json(['data' => $order, 'message' => 'Successfully Get Order Detail', 'status' => 'success'], 200);
    }

    public function updateScheduleDateByRider(Request $request)
    {
        $order_id = $request->order_id;
        $data = $request->all();
        $order    = $this->orderService->updateOrderScheduleDateByRider($data, $order_id);
        return response()->json(['data' => $order, 'message' => 'Successfully Update Order Schedule Date By Rider', 'status' => 'success'], 200);
    }

    public function updatePaymentChannel(Request $request)
    {
        $order_id = $request->order_id;
        $data = $request->all();
        $order    = $this->orderService->updatePaymentChannel($data, $order_id);
        return response()->json(['data' => $order, 'message' => 'Successfully Update Payment Channel', 'status' => 'success'], 200);
    }
}
