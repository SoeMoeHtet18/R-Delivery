<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiderCreateApiRequest;
use App\Http\Requests\RiderLoginApiRequest;
use App\Http\Requests\RiderUpdateApiRequest;
use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\CustomerCollection;
use App\Models\Deficit;
use App\Models\Order;
use App\Models\Rider;
use App\Models\RiderPayment;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RiderRepository;
use App\Repositories\TownshipRepository;
use App\Services\CustomerCollectionService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\RiderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RiderApiController extends Controller
{
    protected $riderRepository;
    protected $riderService;
    protected $orderRepository;
    protected $orderService;
    protected $townshipRepository;
    protected $notificationRepository;
    protected $notificationservice;
    protected $customerCollectionService;

    public function __construct(RiderRepository $riderRepository, RiderService $riderService, OrderRepository $orderRepository, OrderService $orderService, TownshipRepository $townshipRepository, NotificationRepository $notificationRepository, NotificationService $notificationservice, CustomerCollectionService $customerCollectionService)
    {
        $this->riderRepository = $riderRepository;
        $this->riderService = $riderService;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->townshipRepository = $townshipRepository;
        $this->notificationRepository = $notificationRepository;
        $this->notificationservice = $notificationservice;
        $this->customerCollectionService = $customerCollectionService;
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
        $townships = $rider->townships;
        $township_name = [];
        foreach($townships as $township){
            $township_name[] = $township->name; 
        }
        $rider['township_name'] = implode(',',$township_name);
        return response()->json(['data' => $rider, 'message' => 'Successfully Get Rider Detail', 'status' => 'success'], 200);
    }

    public function getOrderList()
    {
        $rider = auth()->guard('rider-api')->user();
        $orders = $this->riderRepository->getOrderList($rider->id);
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
        return response()->json(['data' => $data, 'message' => 'Successfully Change Order Status', 'status' => 'success'], 200);
    }

    public function getAllRidersByTownshipID(Request $request)
    {
        $township_id = $request->township_id;
        $township = $this->townshipRepository->getTownshipById($township_id);
        $riders = $township->riders;
        return response()->json(['data' => $riders, 'message' => 'Successfully Get Riders By Township', 'status' => 'success'], 200);
    }

    public function getNotifications()
    {
        $rider = auth()->guard('rider-api')->user();
        $notifications = $this->notificationRepository->getNotifications(Rider::class, $rider->id);
        return response()->json(['data' => $notifications, 'message' => 'Successfully Get Notifications', 'status' => 'success'], 200);
    }

    public function removeNotification(Request $request)
    {
        $rider = auth()->guard('rider-api')->user();
        $notification_id = $request->notification_id;
        $notifications = $this->notificationservice->removeNotificationByUser($notification_id, $rider->id, Rider::class);
        return response()->json(['data' => $notifications, 'message' => 'Successfully Remove Notification', 'status' => 'success'], 200);

    }

    public function makeNoticationRead(Request $request){
        $rider = auth()->guard('rider-api')->user();
        $notification_id = $request->notification_id;
        $notifications = $this->notificationservice->makeNotificationReadByUser($notification_id, $rider->id, Rider::class);
        return response()->json(['data' => $notifications, 'message' => 'Successfully make notification read', 'status' => 'success'], 200);
    }

    public function getNotificationCount()
    {
        $rider = auth()->guard('rider-api')->user();
        $notifications = $this->notificationRepository->getNotificationCount(Rider::class,$rider->id);
        return response()->json(['data' => $notifications, 'message' => 'Successfully get notification count', 'status' => 'success'], 200);
    }

    public function changePassword(Request $request)
    {
        $old_password = $request->oldPassword;
        $new_password = $request->newPassword;
        $rider = auth()->guard('rider-api')->user();
        $password = $this->riderService->changePassword($rider, $old_password, $new_password);
        if($password) {
            return response()->json(['data' => $rider, 'message' => 'Successfully changed password', 'status' => 'success'], 200);
        } else {
            return response()->json(['data' => $rider, 'message' => 'Password wronged', 'status' => 'failed'], 200);
        }
    }

    public function getTotalCollectionFees()
    {
        $rider = auth()->guard('rider-api')->user();
        $rider_fees = $rider->townships->first()->pivot->rider_fees;
        if($rider->salary_type == 'daily') {
            $currentDate = Carbon::now()->format('Y-m-d');
            $collection_count = Collection::where('rider_id',$rider->id)->whereDate('collected_at', $currentDate)->count();
            $total_collection_fees = $rider_fees * $collection_count;
            return response()->json(['data' => $total_collection_fees, 'message' => 'Successfully Get Total Collectoin Fees of Rider', 'status' => 'success'], 200);
        } else {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
            $collection_count = Collection::where('rider_id', $rider->id)->whereBetween('collected_at', [$startDate, $endDate])->count();
            $total_collection_fees = $rider_fees * $collection_count;
            return response()->json(['data' => $total_collection_fees, 'message' => 'Successfully Get Total Collectoin Fees of Rider', 'status' => 'success'], 200);
        }
    }

    public function getTotalDeliveryFees()
    {
        $rider = auth()->guard('rider-api')->user();
        $rider_fees = $rider->townships->first()->pivot->rider_fees;
        if($rider->salary_type == 'daily') {
            $currentDate = Carbon::now()->format('Y-m-d');
            $order_count = Order::where('rider_id',$rider->id)->where('status','success')->whereDate('schedule_date', $currentDate)->count();
            $total_deli_fees = $rider_fees * $order_count;
            return response()->json(['data' => $total_deli_fees, 'message' => 'Successfully Get Total Delivery Fees of Rider', 'status' => 'success'], 200);
        } else {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
            $order_count = Order::where('rider_id', $rider->id)->where('status','success')->whereBetween('schedule_date', [$startDate, $endDate])->count();
            $total_deli_fees = $rider_fees * $order_count;
            return response()->json(['data' => $total_deli_fees, 'message' => 'Successfully Get Total Delivery Fees of Rider', 'status' => 'success'], 200);
        }
    }

    // public function getRiderTotalSalary()
    // {
    //     $rider = auth()->guard('rider-api')->user();
    //     $deliFees = 0;
    //     $totalCollectionFees = 0;
    //     $totalPickUpCount = 0;
    //     $orderCount = 0;
    //     if($rider->salary_type == 'daily') {
    //         $currentDate = Carbon::now()->format('Y-m-d');
    //         $deliveredOrders = Order::where('rider_id',$rider->id)
    //             ->where('status','success')->whereDate('schedule_date', $currentDate)->get();
    //         $orderCount = Order::where('rider_id',$rider->id)
    //             ->where('status','success')->whereDate('schedule_date', $currentDate)->count();
    //         foreach($deliveredOrders as $order) {
    //             $riderFee = DB::table('rider_township')
    //                 ->where(['rider_id'=>$rider->id,'township_id'=>$order->township_id])->first()->rider_fees;
    //             $deliFees  += $riderFee;
    //         }
            
    //         $collections = Collection::whereDate('collected_at',$currentDate)
    //             ->where('rider_id',$rider->id)->get();
    //         $customerExchanges = CustomerCollection::whereDate('complete_at',$currentDate)
    //             ->where(['rider_id' => $rider->id, 'is_way_fees_payable' => true])->get();
    //         foreach($customerExchanges as $customerExchange){
    //             $riderFee = DB::table('rider_township')
    //                 ->where(['rider_id'=>$rider->id,'township_id'=>$customerExchange->township_id])
    //                 ->first()->rider_fees;
    //             $totalCollectionFees += $riderFee;
    //         }
    //         $collectionCount = Collection::whereDate('collected_at',$currentDate)
    //             ->where('rider_id',$rider->id)->count();
    //         $customerExchangeCount = CustomerCollection::whereDate('complete_at',$currentDate)
    //             ->where(['rider_id' => $rider->id, 'is_way_fees_payable' => true])->count();
    //         $totalPickUpCount = $collectionCount + $customerExchangeCount;
            
    //         $deficitFees = Deficit::where('rider_id',$rider->id)
    //             ->whereDate('created_at',$currentDate)->sum('total_amount');
    //     } else {
    //         $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
    //         $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    //         $deliveredOrders = Order::where('rider_id',$rider->id)
    //             ->where('status','success')->whereBetween('schedule_date', [$startDate, $endDate])->get();
    //         $orderCount = Order::where('rider_id', $rider->id)
    //             ->where('status','success')->whereBetween('schedule_date', [$startDate, $endDate])->count();
            
    //         foreach($deliveredOrders as $order) {
    //             $riderFee = DB::table('rider_township')
    //                 ->where(['rider_id'=>$rider->id,'township_id'=>$order->township_id])->first()->rider_fees;
    //             $deliFees  += $riderFee;
    //         }
            
    //         $collections = Collection::whereBetween('collected_at', [$startDate, $endDate])
    //             ->where('rider_id',$rider->id)->get();
    //         $customerExchanges = CustomerCollection::whereBetween('complete_at', [$startDate, $endDate])
    //             ->where(['rider_id' => $rider->id, 'is_way_fees_payable' => true])->get();
    //         foreach($customerExchanges as $customerExchange){
    //             $riderFee = DB::table('rider_township')
    //                 ->where(['rider_id'=>$rider->id,'township_id'=>$customerExchange->township_id])
    //                 ->first()->rider_fees;
    //             $totalCollectionFees += $riderFee;
    //         }
    //         $collectionCount = Collection::whereBetween('collected_at', [$startDate, $endDate])
    //             ->where('rider_id',$rider->id)->count();
    //         $customerExchangeCount = CustomerCollection::whereBetween('complete_at', [$startDate, $endDate])
    //             ->where(['rider_id' => $rider->id, 'is_way_fees_payable' => true])->count();
    //         $totalPickUpCount = $collectionCount + $customerExchangeCount;

    //         $deficitFees = Deficit::where('rider_id',$rider->id)
    //             ->whereBetween('created_at',[$startDate, $endDate])->sum('total_amount');
    //     }

    //     $totalSalary    = ($totalCollectionFees + $deliFees + $rider->base_salary) - $deficitFees;
    //     $data = [];
    //     $data['total_salary'] = $totalSalary;
    //     $data['deficit_fees'] = $deliFees;
    //     $data['collection_count'] = $totalPickUpCount;
    //     $data['order_count'] = $orderCount;
    //     return response()->json([
    //         'data' => $data,
    //         'message' => 'Successfully Get Total Salary for Rider',
    //         'status' => 'success'], 200);
    // }

    public function getRiderTotalSalary()
    {
        $rider = auth()->guard('rider-api')->user();
        $currentDate = Carbon::now();
        $startDate = $rider->salary_type == 'daily' ?
                $currentDate->format('Y-m-d') : $currentDate->startOfMonth()->format('Y-m-d');
        $endDate = $rider->salary_type == 'daily' ?
                $currentDate->format('Y-m-d').' 23:59:59' : $currentDate->endOfMonth()->format('Y-m-d').' 23:59:59';

        $deliveredOrders = $this->getDeliveredOrders($rider, $startDate, $endDate);
        $orderCount = $deliveredOrders->count();
        $deliFees = $this->calculateFees($deliveredOrders, $rider->id);

        $collections = $this->getCollections($rider, $startDate, $endDate);
        $customerExchanges = $this->getCustomerExchanges($rider, $startDate, $endDate);
        $totalCollectionFees = $this->calculateFees($customerExchanges, $rider->id);
        $totalPickUpCount = $collections->count() + $customerExchanges->count();

        $deficitFees = $this->getDeficitFees($rider, $startDate, $endDate);
        // dd($totalCollectionFees + $deliFees + $rider->base_salary);

        $totalSalary = ($totalCollectionFees + $deliFees + $rider->base_salary) - $deficitFees;

        $data = [
            'total_salary' => $totalSalary,
            'deficit_fees' => $deficitFees,
            'collection_count' => $totalPickUpCount,
            'order_count' => $orderCount,
        ];

        return response()->json([
            'data' => $data,
            'message' => 'Successfully Get Total Salary for Rider',
            'status' => 'success'
        ], 200);
    }

    private function calculateFees($items, $riderId)
    {
        $totalFees = 0;

        foreach ($items as $item) {
            $riderFee = DB::table('rider_township')
                ->where(['rider_id' => $riderId, 'township_id' => $item->township_id])
                ->first()->rider_fees;

            $totalFees += $riderFee;
        }

        return $totalFees;
    }

    private function getDeliveredOrders($rider, $startDate, $endDate)
    {
        return Order::where('rider_id', $rider->id)
            ->where('status', 'success')
            ->whereBetween('schedule_date', [$startDate, $endDate])
            ->get();
    }

    private function getCollections($rider, $startDate, $endDate)
    {
        return Collection::whereBetween('collected_at', [$startDate, $endDate])
            ->where('rider_id', $rider->id)
            ->get();
    }

    private function getCustomerExchanges($rider, $startDate, $endDate)
    {
        return CustomerCollection::whereBetween('complete_at', [$startDate, $endDate])
            ->where(['rider_id' => $rider->id, 'is_way_fees_payable' => true])
            ->get();
    }

    private function getDeficitFees($rider, $startDate, $endDate)
    {
        return Deficit::where('rider_id', $rider->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
    }

    public function getCustomerCollectionByRiderId($page = 1) {
        $rider = auth()->guard('rider-api')->user();
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $todayDate = Carbon::now()->format('Y-m-d');
        $customerCollections = CustomerCollection::with(['order', 'collection_group', 'shop', 'rider', 'city', 'township'])
            ->where('rider_id', $rider->id)->whereDate('schedule_date',$todayDate)
            ->offset($offset)->limit($limit)->orderBy('id','DESC')->get();
        foreach($customerCollections as $customerCollection) {
            $customerCollection['rider_name'] = $customerCollection->rider ? $customerCollection->rider->name : null;
            $customerCollection['shop_name'] = $customerCollection->shop ? $customerCollection->shop->name : null;
            $customerCollection['customer_name'] = $customerCollection->order ? $customerCollection->order->customer_name : null;
            $customerCollection['customer_phone_number'] = $customerCollection->order ? $customerCollection->order->customer_phone_number : null;
            $customerCollection['total_amount'] = $customerCollection->order? $customerCollection->order->total_amount : null;
            $customerCollection['order_id'] = $customerCollection->order ? $customerCollection->order->order_code : null;
            $customerCollection['city_name'] = $customerCollection->city ? $customerCollection->city->name : null;
            $customerCollection['township_name'] = $customerCollection->township ? $customerCollection->township->name : null;
        }
        return response()->json([
            'data' => $customerCollections,
            'message' => 'Successfully Get Customer Collection list by Rider Id',
            'status' => 'success'], 200);
    }
    
    public function createCustomerCollectionByRider(Request $request) {
        $rider = auth()->guard('rider-api')->user();
        $data = $request->all();
        $customerCollection = $this->customerCollectionService->saveCustomerCollectionByRider($rider, $data);
        return response()->json(['data' => $customerCollection, 'message' => 'Successfully Create Customer Collection by Rider', 'status' => 'success'], 200);
    }

    public function getRiderPaymentHistory($page = 1) {
        $rider = auth()->guard('rider-api')->user();
        $limit = 10; 
        $offset = ($page - 1) * $limit; 
        $riderPayments = RiderPayment::where('rider_id',$rider->id)->offset($offset)->limit($limit)->orderBy('id','DESC')->get();
        return response()->json(['data' => $riderPayments, 'message' => 'Successfully Get Rider Payment History', 'status' => 'success'], 200);
    }
    
    public function updateCustomerCollectionByRider(Request $request) {
        $rider = auth()->guard('rider-api')->user();
        $data = $request->all();
        $reuploadPhoto = false;
        if($request->has('photo')){
            $reuploadPhoto = true;
        }
        $customerCollection = $this->customerCollectionService->updateCustomerCollectionByRider($data,$reuploadPhoto);
        return response()->json(['data' => $customerCollection, 'message' => 'Successfully Update Customer Collection by Rider', 'status' => 'success'], 200);
    } 
    
    public function checkOrderRider($order_code) {
        $rider_id = auth()->guard('rider-api')->user()->id;
        $order = Order::where('order_code',$order_code)->first();
        if($order->rider_id == $rider_id) {
            return response()->json(['data' => $order->id, 'message' => 'this order is assign for current rider.', 'status' => 'success'], 200);
        } else {
            return response()->json(['data' => $order->id, 'message' => 'this order is not assign for current rider.', 'status' => 'fail'], 200);
        }
    }
}
