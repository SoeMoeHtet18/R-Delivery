<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rider;
use App\Repositories\CollectionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ReportCalculationRepository;
use App\Repositories\ShopRepository;
use App\Repositories\TransactionsForShopRepository;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    protected $orderRepository;
    protected $orderService;
    protected $collectionRepository;
    protected $transactionForShopRepository;
    protected $shopRepository;
    protected $reportCalculationRepository;

    public function __construct(OrderRepository $orderRepository, OrderService $orderService,
        CollectionRepository $collectionRepository, TransactionsForShopRepository $transactionForShopRepository,
        ShopRepository $shopRepository, ReportCalculationRepository $reportCalculationRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->collectionRepository = $collectionRepository;
        $this->transactionForShopRepository = $transactionForShopRepository;
        $this->shopRepository = $shopRepository;
        $this->reportCalculationRepository = $reportCalculationRepository;
    }

    public function getOrderCode(Request $request)
    {
        $shop_id = $request->shop_id;
        $order_code = Helper::nomenclature('orders', 'TCP', 'id', $shop_id, 'S');
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
        $totalCredit = $this->reportCalculationRepository->getTotalCreditForShop($shop_id);

        // $paid_credit_from_collection = $this->collectionRepository->getPaidAmountByShopUser($shop_id);
        // $paid_credit_from_transaction = $this->transactionForShopRepository->getPaidAmountByShopUser($shop_id);
        // $total_amount = strval($total_credit - ($paid_credit_from_collection + $paid_credit_from_transaction));
        
        return response()->json(['data' => $totalCredit, 'message' => 'Successfully Get Orders Total Amount By Shop ID', 'status' => 'success'], 200);
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
        $rider = auth()->guard('rider-api')->user();

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
            $status = $this->orderService->changeStatus($order, 'success', $rider, Rider::class);
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

    public function getDataByOrderForCustomerCollection(Request $request)
    {
        $order_id = $request->order_id;
        $data = $this->orderRepository->getDataByOrder($order_id);
        return response()->json(['data' => $data, 'message' => 'Successfully get data by order', 'status' => 'success'], 200);
    }

    public function cancelPaymentChannel(Request $request)
    {
        $order_id = $request->order_id;
        $order    = $this->orderService->cancelPaymentChannel($order_id);
        return response()->json(['data' => $order, 'message' => 'Successfully Update Payment Channel', 'status' => 'success'], 200);
    }

    public function getOrderDetailByShop(Request $request)
    {
        $order_id = $request->order_id;
        $order = $this->orderRepository->getOrderDetailByShop($order_id);
        return response()->json(['data' => $order, 'message' => 'Successfully Get Order Detail', 'status' => 'success'], 200);
    }

    /**
     * Return total amount of the delivered order by rider with today date
     */
    public function getOrderTotalAmount(Request $request)
    {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $date = Carbon::today();
        if($request->date){
            $date = Carbon::parse($request->date)->format('Y-m-d');
        }
        $data  = $this->orderRepository->getOrderTotalAmount($rider_id, $date);

        return response()->json([
            'data' => $data,
            'message' => 'Successfully Get Order Total Amount',
            'status' => 'success'
        ], 200);
    }

    public function reAssignRider($order_id)
    {
        $rider = auth()->guard('rider-api')->user();
        $order = $this->orderRepository->getOrderByID($order_id);
        $data = $this->orderService->reAssignRider($order, $rider);

        return response()->json([
            'data'   => $data,
            'message'=> 'Successfully change rider for order',
            'status' => 'success'
        ], 200);
    }

    public function saveBulkOrder(Request $request) {
        $bulkOrder = $request->bulkOrder;
        foreach($bulkOrder as $data){
            $this->orderService->saveBulkOrderData($data);
        }
        return response()->json([
            'data'   => null,
            'message'=> 'Successfully save bulk order',
            'status' => 'success'
        ], 200);
    }

    public function saveOrder(Request $request) {
        $data = $request->data;
        $order = $this->orderService->saveBulkOrderData($data);
        return response()->json([
            'data'   => $order->id,
            'message'=> 'Successfully save bulk order',
            'status' => 'success'
        ], 200);
    }

    public function updateOrder(Request $request)
    {
        $data = $request->data;
        $order = $this->orderService->updateBulkOrder($data);
        return response()->json([
            'data'   => $order->id,
            'message'=> 'Successfully update bulk order',
            'status' => 'success'
        ], 200);
    }
}
