<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\CustomerCollection;
use App\Models\Deficit;
use App\Models\Gate;
use App\Models\Order;
use App\Models\ShopPayment;
use App\Models\Township;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderRepository
{
    public function getOrderByID($id)
    {
        $order = Order::with(['shop','rider','city','township','itemType','delivery_type'])->findOrFail($id);
        return $order;
    }
    public function getAllOrders()
    {
        $branch_id = auth()->user()->branch_id;
        $order = Order::where('branch_id', $branch_id)->get();
        return $order;
    }
    
    public function getAllOrdersQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->leftJoin('branches', 'branches.id', 'orders.branch_id')
            ->leftJoin('collection_groups', 'collection_groups.id', 'orders.collection_group_id')
            ->where('orders.branch_id', $branch_id)
            ->select('orders.*', 'townships.name as township_name', 
                'shops.name as shop_name', 
                'riders.name as rider_name', 
                'users.name as last_updated_by_name', 
                'cities.name as city_name', 
                'item_types.name as item_type_name',
                'delivery_types.name as delivery_type_name',
                'branches.name as branch_name',
                'collection_groups.collection_group_code as collection_group_code',
                'collection_groups.assigned_date as pick_up_date',
            );
        return $query;
    }

    public function getOrderByCustomerPhoneNumber($phone_number)
    {
        $data = Order::where('customer_phone_number', $phone_number)->first();
        return $data;
    }

    public function getOrdersByShopID($id, $status, $start_date, $end_date, $page)
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        if ($status == 'success') {
            $orders = Order::where('shop_id', $id)
                ->where('status', 'success');
        } else if ($status == 'canceled') {
            $orders = Order::where('shop_id', $id)
                ->where('status', 'cancel');
        } else {
            $orders = Order::where('shop_id', $id)
                ->whereNot('status', 'success')
                ->whereNot('status', 'cancel');
        }
        
        $orders->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->select('orders.*', 'cities.name as city_name', 
                'townships.name as township_name', 
                'item_types.name as item_type_name', 
                'delivery_types.name as delivery_type_name');

        if($start_date != 'null' && $end_date != 'null') {
            $orders = $orders->whereBetween('orders.schedule_date', [$start_date, $end_date]);
        }

        $orders = $orders->offset($offset)->limit($limit)->orderBy('id','DESC')->get();
        return $orders;
    }

    public function getAllOrdersCount()
    {
        $user = auth()->user();
        return Order::where('branch_id', $user->branch_id)->count();
    }

    public function getOrdersStatusCountByShopID($shop_id)
    {
        $count = Order::selectRaw('COUNT(*) as total_order')
            ->selectRaw('COUNT(CASE WHEN status NOT IN ("success", "cancel") THEN 1 END) as on_going')
            ->selectRaw('COUNT(CASE WHEN status = "success" THEN 1 END) as success')
            ->selectRaw('COUNT(CASE WHEN status = "cancel" THEN 1 END) as cancel')
            ->where('shop_id', $shop_id)
            ->first();

        // Convert the results to an associative array
        $result = [
            'on_going' => $count->on_going ?? 0,
            'success' => $count->success ?? 0,
            'cancel' => $count->cancel ?? 0,
            'total_order' => $count->total_order ?? 0,
        ];

        return $result;
    }


    public function getOrdersTotalAmountByShopID($shop_id)
    {
        $total_amount = Order::where('shop_id', $shop_id)
            ->where('status', 'success')
            ->selectRaw('SUM(total_amount + markup_delivery_fees) AS total_amount')
            ->first();

        return $total_amount;
    }

    public function getOrdersStatusCountByRiderID($rider_id)
    {
        $status = ['pending', 'success', 'delay', 'cancel'];
        $orders = Order::where('rider_id', $rider_id)
            ->select('status', DB::raw('count(*) as count'))
            ->whereIn('status', $status)
            ->groupBy('status')
            ->get();
        $count = [];
        foreach ($status as $s) {
            $count[$s] = 0;
        }

        foreach ($orders as $order) {
            $count[$order->status] = $order->count;
        }

        $count['total_order'] = array_sum($count);
        return $count;
    }

    public function getOrdersTotalAmountByRiderID($rider_id, $list_status)
    {
        $total_amount = Order::where('rider_id', $rider_id)
            ->where('status', 'success')
            ->where('payment_flag', 0)
            ->selectRaw('SUM(total_amount + delivery_fees + markup_delivery_fees) AS total_amount')
            ->first();


        return $total_amount;

        // $today = Carbon::today();

        // if ($list_status == 'one day') {
        //     $total_amount = Order::where('rider_id', $rider_id)
        //         ->whereDate('schedule_date', $today)
        //         ->whereIn('orders.status', ['pending', 'delay'])
        //         ->selectRaw('SUM(total_amount + IF(markup_delivery_fees = 0, delivery_fees, markup_delivery_fees)) AS total_amount')
        //         ->first();
        // }        
        // if ($list_status == 'history') {
        //     $total_amount = Order::where('rider_id', $rider_id)
        //         ->whereDate('schedule_date', '>', $today)
        //         ->whereIn('orders.status', ['pending', 'delay'])
        //         ->selectRaw('SUM(total_amount + IF(markup_delivery_fees = 0, delivery_fees, markup_delivery_fees)) AS total_amount')
        //         ->first();
        // }

        // if ($list_status == 'upcoming') {
        //     $total_amount = Order::where('rider_id', $rider_id)
        //         ->where('status', 'success')
        //         ->selectRaw('SUM(total_amount + IF(markup_delivery_fees = 0, delivery_fees, markup_delivery_fees)) AS total_amount')
        //         ->first();
        // }

        // return $total_amount;
    }

    public function getOneDayOrderList($rider_id)
    {
        $today = Carbon::today();
        $orders = Order::where('orders.rider_id', $rider_id)
            ->whereIn('orders.status', ['delivering', 'delay', 'cancel_request'])
            ->whereDate('orders.schedule_date', $today)
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->select('orders.*', 'shops.name as shop_name')
            ->orderBy('updated_at', 'asc')
            ->get();
        return $orders;
    }

    public function getUpcomingOrderList($rider_id, $start_date, $end_date, $page)
    {
        $today = Carbon::today();
        $limit = 10; 
        $offset = ($page - 1) * $limit; 
        $orders = Order::where('orders.rider_id', $rider_id)
            ->whereIn('orders.status', ['pending', 'delay', 'picking-up', 'warehouse'])
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->select('orders.*', 'shops.name as shop_name');
            
        if($start_date != 'null' && $end_date != 'null') {
            $orders = $orders->whereBetween('orders.schedule_date', [$start_date, $end_date]);
        } else {
            $orders = $orders->whereDate('orders.schedule_date', '>', $today);
        }
        $orders = $orders->offset($offset)->limit($limit)->orderBy('orders.id','DESC')->get();
        return $orders;
    }

    public function getOrderHistoryList($rider_id, $start_date, $end_date, $page)
    {
        $limit = 10; 
        $offset = ($page - 1) * $limit; 
        $currentDate = Carbon::now()->format('Y-m-d');
        $orders = Order::with('successLog')->where('orders.rider_id', $rider_id)
            ->where('status', 'success')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->select('orders.*', 'shops.name as shop_name');
        
        if($start_date != 'null' && $end_date != 'null') {
            $orders = $orders->whereBetween('orders.schedule_date', [$start_date, $end_date]);
        } else {
            $orders = $orders->whereDate('orders.schedule_date', $currentDate);
        }
        $orders = $orders->offset($offset)->limit($limit)->orderBy('orders.id','DESC')->get();
        foreach($orders as $order) {
            $order['delivered_at'] = $order->successLog ? $order->successLog->created_at : null;
        }
        return $orders;
    }


    public function getOrderListCount($rider_id)
    {
        $status = ['one day', 'upcoming', 'history'];
        $today = Carbon::today();

        $one_day = Order::where('rider_id', $rider_id)
            ->whereIn('orders.status', ['delivering', 'delay', 'cancel_request'])
            ->whereDate('schedule_date', $today)
            ->select('status')
            ->count();

        $upcoming = Order::where('rider_id', $rider_id)
            ->whereDate('schedule_date', '>', $today)
            ->whereIn('orders.status', ['pending', 'delay', 'picking-up', 'warehouse'])
            ->select('status')
            ->count();

        $history = Order::where('rider_id', $rider_id)
            ->where('status', 'success')
            ->whereDate('schedule_date', $today)
            ->select('status')
            ->count();

        $count = [];
        foreach ($status as $s) {
            $count[$s] = 0;
        }

        if ($one_day) {
            $count['one day'] = $one_day;
        }

        if ($upcoming) {
            $count['upcoming'] = $upcoming;
        }

        if ($history) {
            $count['history'] = $history;
        }

        return $count;
    }

    public function getOrderDetailWithRelatedData($id)
    {
        $order = Order::with('successLog')->where('orders.id', $id)
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->select('orders.*', 'shops.name as shop_name', 'cities.name as city_name', 'townships.name as township_name', 'item_types.name as item_type_name', 'delivery_types.name as delivery_type_name')
            ->first();
        $township = Township::where('id',$order->township_id)->first();
        if($township->associable_type == Gate::class) {
            $gate = $township->associable;
            // dd($gate);
            $order['full_address'] = $gate->address;
        }
        // if (Storage::exists('order_data.txt')) {
        //     $orderDataJson = Storage::get('order_data.txt');
        //     $orders = json_decode($orderDataJson, true);
        // }
        // $order_code = $order->order_code;
        $order['delivered_at'] = $order->successLog ? $order->successLog->created_at : null;
        // if (isset($orders[$order_code])) {
        //     $orderData = $orders[$order_code];
        //     if(isset($orderData['delivered_at'])){
        //         $order['delivered_at'] = $orderData['delivered_at'];
        //     }
        // }
        // dd($order);

        return $order;
    }

    public function trackOrderByOrderID($id)
    {
        $order = Order::where('orders.order_code', $id)
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->select('orders.*', 'shops.name as shop_name', 'riders.name as rider_name',
                'riders.phone_number as rider_phone_number')
            ->first();
        return $order;
    }

    // public function getAllOrderIdsByShopID($id)
    // {
    //     $orders = Order::where('shop_id', $id)->pluck('id')->toArray();
    //     return $orders;
    // }

    public function getCancelRequestOrdersQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = Order::where('orders.status', 'cancel_request')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('branches', 'branches.id', 'orders.branch_id')
            ->where('orders.branch_id', $branch_id)
            ->select('orders.*',  'shops.name as shop_name', 'riders.name as rider_name',
                'branches.name as branch_name');
        return $query;
    }
    
    public function getCancelOrdersQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = Order::where('orders.status', 'cancel')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('branches', 'branches.id', 'orders.branch_id')
            ->where('orders.branch_id', $branch_id)
            ->select('orders.*',  'shops.name as shop_name', 'riders.name as rider_name',
                'branches.name as branch_name');
        return $query;
    }
    
    public function getWarehouseOrderListQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = Order::where('orders.status', 'warehouse')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('branches', 'branches.id', 'orders.branch_id')
            ->where('orders.branch_id', $branch_id)
            ->select('orders.*',  'shops.name as shop_name', 'riders.name as rider_name',
                'branches.name as branch_name');
        return $query;
    }

    public function getWarehouseOrdersQuery()
    {
        $query = Order::where('orders.status', 'warehouse')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('townships', 'townships.id', 'orders.township_id')
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'cities.name as city_name');
        return $query;
    }

    // public function getTotalCreditForShop($shopId)
    // {
    //     $codAmount = Order::where('shop_id', $shopId)
    //                     ->where('payment_method', 'cash_on_delivery')
    //                     ->sum(DB::raw('total_amount + markup_delivery_fees'));

    //     $remainingAmount = Order::where('shop_id', $shopId)
    //                         ->whereNot('payment_method', 'cash_on_delivery')
    //                         ->sum('markup_delivery_fees');

    //     $customerCollectionAmount = CustomerCollection::where('shop_id', $shopId)->sum('paid_amount');
        
    //     return $codAmount + $remainingAmount - $customerCollectionAmount;
    // }

    public function getAllUnpaidOrderList()
    {
        $branch_id = auth()->user()->branch_id;
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->where('orders.payment_flag',0)
            ->where('orders.status','success')
            ->where('orders.branch_id', $branch_id)
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name',
                'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name',
                'item_types.name as item_type_name', 'delivery_types.name as delivery_type_name',
                'delivery_types.notified_on as notified_on');
        return $query;
    }

    public function getOrdersByIds($order_ids)
    {
        // Check if $order_ids is a string, then convert it to an array
        if (is_string($order_ids)) {
            $order_ids = explode(',', $order_ids);
        }

        // Make sure $order_ids is an array before proceeding
        if (!is_array($order_ids)) {
            return response()->json(['error' => 'Invalid input'], 400);
        }

        // Remove any empty elements from the array
        $order_ids = array_filter($order_ids, 'strlen');

        // Fetch the orders using the filtered array of IDs
        $orders = Order::whereIn('id', $order_ids)->get();

        return $orders;
    }

    public function getDataByOrder($id) {
        $data = Order::where('id',$id)
            ->select('shop_id','customer_name', 'customer_phone_number', 'rider_id', 'city_id', 'township_id', 'full_address')
            ->first();
        return $data;
    }

    public function getAllOrdersQueryByShop($id) 
    {
        $query = Order::where('shop_id', $id)
            ->with('township','shop','rider');
        return $query;
    }

    public function getAllPendingOrdersByRider($id)
    {
        $orders = Order::where('rider_id',$id)
            ->where('status','pending')
            ->with('township','shop','rider')
            ->get();
        return $orders;
    }

    public function getOrderDetailByShop($id)
    {
        $order = Order::where('orders.id', $id)
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->select('orders.*', 'cities.name as city_name', 
                'townships.name as township_name', 
                'item_types.name as item_type_name', 
                'delivery_types.name as delivery_type_name')->first();

        $orders = [];
        if (Storage::exists('order_data.txt')) {
            $orderDataJson = Storage::get('order_data.txt');
            $orders = json_decode($orderDataJson, true);
        }
        $order['delivered_at'] = null;

        if (isset($orders[$order->order_code])) {
            $orderData = $orders[$order->order_code];
            if(isset($orderData['delivered_at'])) {
                $order['delivered_at'] = $orderData['delivered_at'];
            }
        }

        return $order;
    }

    public function getOrderTotalAmount($rider_id, $date)
    {
        $commonConditions = [
            'rider_id' => $rider_id,
            'status' => 'success'
        ];

        $cashOnDeliQuery = Order::where($commonConditions)
            ->where('payment_channel', 'cash')
            ->whereDate('schedule_date', $date)
            ->where('payment_method', 'cash_on_delivery');

        $itemPrepaidQuery = Order::where($commonConditions)
            ->where('payment_channel', 'cash')
            ->whereDate('schedule_date', $date)
            ->where('payment_method', 'item_prepaid');

        $onlinePaymentQuery = Order::where($commonConditions)
            ->whereIn('payment_channel', ['shop_online_payment', 'company_online_payment'])
            ->whereDate('schedule_date', $date)
            ->where('payment_method', 'cash_on_delivery');

        $onlineItemPrepaidQuery = Order::where($commonConditions)
            ->whereIn('payment_channel', ['shop_online_payment', 'company_online_payment'])
            ->whereDate('schedule_date', $date)
            ->where('payment_method', 'item_prepaid');
        
        $paymentFromCompany = CollectionGroup::where('rider_id',$rider_id)
            ->whereDate('assigned_date', $date)->sum('total_amount');

        $cashTotalAmount = strval($cashOnDeliQuery->selectRaw('SUM(total_amount + delivery_fees + COALESCE(markup_delivery_fees, 0) + extra_charges - COALESCE(discount, 0)) AS total_amount')
            ->first()->total_amount + $itemPrepaidQuery->selectRaw('SUM(delivery_fees + COALESCE(markup_delivery_fees, 0) + extra_charges - COALESCE(discount, 0)) AS total_amount')
            ->first()->total_amount);

        $onlinePaymentTotalAmount = strval($onlinePaymentQuery->selectRaw('SUM(total_amount + delivery_fees + COALESCE(markup_delivery_fees, 0) + extra_charges - COALESCE(discount, 0)) AS total_amount')
            ->first()->total_amount + $onlineItemPrepaidQuery->selectRaw('SUM(delivery_fees + COALESCE(markup_delivery_fees, 0) + extra_charges - COALESCE(discount, 0)) AS total_amount')
            ->first()->total_amount);

        $deficit_fees = Deficit::where('rider_id', $rider_id)
            ->whereDate('created_at', $date)
            ->sum('total_amount');

        return [
            'payment_from_company' => $paymentFromCompany,
            'cash_total_amount' => $cashTotalAmount,
            'online_payment_total_amount' => $onlinePaymentTotalAmount,
            'deficit_fees' => $deficit_fees,
        ];
    }

    
    public function getTodayOrdersByRider($id)
    {
        $today = Carbon::today();
        $orders = Order::where('rider_id',$id)
            ->whereDate('schedule_date', $today)
            ->where('status','delivering')
            ->with('township','shop','rider')
            ->get();
        return $orders;
    }

    public function getOrderByOrderCode($order_code)
    {
        $order = Order::with(['shop','rider'])->where('order_code',$order_code)->firstOrFail();
        return $order;
    }

    public function getCurrentOrdersQuery($id)
    {
        $branch_id = auth()->user()->branch_id;
        return Order::leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->leftJoin('branches', 'branches.id', 'orders.branch_id')
            ->leftJoin('collection_groups', 'collection_groups.id', 'orders.collection_group_id')
            ->where('orders.branch_id', $branch_id)
            ->where('orders.township_id', $id)
            ->whereNotIn('status', ['success', 'cancel_request', 'cancel'])
            ->select('orders.*', 'shops.name as shop_name',
                'riders.name as rider_name',
                'users.name as last_updated_by_name',
                'item_types.name as item_type_name',
                'delivery_types.name as delivery_type_name',
                'branches.name as branch_name',
                'collection_groups.collection_group_code as collection_group_code',
                'collection_groups.assigned_date as pick_up_date',
            );
    }

    public function getAmountsRelatedToOrder($request)
    {
        $pickUpDate = $request->pick_up_date;
        $shopId = $request->shop_id;
        $cityId = $request->city_id;
        $townshipId = $request->township_id;
        $riderId = $request->rider_id;
        $status = $request->status;
        $payLater = $request->pay_later;
        $start = $request->from_date;
        $end = $request->to_date . ' 23:59:00';
        $paymentChannel = $request->payment_channel;

        $cashOnDeliveryInfo = Order::leftJoin('collection_groups', 'collection_groups.id', 'orders.collection_group_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            //filter with pick up date
            ->when(isset($pickUpDate), function ($query) use ($pickUpDate) {
                $query->whereDate('collection_groups.assigned_date', $pickUpDate);
            })
            //filter with shop
            ->when(isset($shopId), function ($query) use ($shopId) {
                $query->where('orders.shop_id', $shopId);
            })
            //filter with city
            ->when(isset($cityId), function ($query) use ($cityId) {
                $query->where('orders.city_id', $cityId);
            })
            //filter with township
            ->when(isset($townshipId), function ($query) use ($townshipId) {
                $query->where('orders.township_id', $townshipId);
            })
            //filter with rider
            ->when(isset($riderId), function ($query) use ($riderId) {
                $query->where('orders.rider_id', $riderId);
            })
            //filter with status
            ->when(isset($status), function ($query) use ($status) {
                $query->where('orders.status', $status);
            })
            //filter with pay later
            ->when(isset($payLater), function ($query) use ($payLater) {
                $query->where('orders.pay_later', $payLater);
            })
            //filter with created Between
             ->when(isset($start) && isset($end), function ($query) use ($start, $end) {
                $query->whereBetween('orders.created_at', [$start, $end]);
            })
            //filter with payment channel
            ->when(isset($paymentChannel), function ($query) use ($paymentChannel) {
                $query->where('orders.payment_channel', $paymentChannel);
            })
            ->selectRaw('SUM(CASE WHEN orders.payment_method = "cash_on_delivery"
                AND (orders.payment_channel != "shop_online_payment" OR orders.payment_channel IS null)
                THEN orders.total_amount END) as totalItemAmount')
            ->selectRaw('SUM(CASE WHEN (orders.payment_method != "all_prepaid")
                AND (orders.payment_channel != "shop_online_payment" OR orders.payment_channel IS null)
                THEN orders.markup_delivery_fees END) as totalMarkUpDeliveryFees')
            ->selectRaw('SUM(CASE WHEN orders.payment_method = "all_prepaid"
                OR (orders.payment_method != "all_prepaid" AND orders.payment_channel = "shop_online_payment")
                THEN orders.delivery_fees + COALESCE(orders.extra_charges, 0)
                - COALESCE(orders.discount, 0) END) as totalDeliveryFees')
            ->first();

        $cashOnDeliveryInfo['totalAmountToPayShop'] = ($cashOnDeliveryInfo->totalItemAmount
            + $cashOnDeliveryInfo->totalMarkUpDeliveryFees) - $cashOnDeliveryInfo->totalDeliveryFees;

        return $cashOnDeliveryInfo;
    }

    public function getShopOrdersByShopID($shop_id, $status, $start, $end)
    {
        if($start && $end) {
            $start = str_replace(' GMT+0630 (Myanmar Time)', '', $start);
            $end = str_replace(' GMT+0630 (Myanmar Time)', '', $end);
            $start = new DateTime($start);
            $end = new DateTime($end);
        }
        
        return Order::where('shop_id', $shop_id)
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->with(['shop', 'city', 'township', 'rider', 'itemType', 'delivery_type', 'branch'])
            ->get();
    }
}
