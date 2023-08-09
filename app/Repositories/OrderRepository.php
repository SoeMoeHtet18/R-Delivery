<?php

namespace App\Repositories;

use App\Models\Gate;
use App\Models\Order;
use App\Models\Township;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderRepository
{
    public function getOrderByID($id)
    {
        $order = Order::findOrFail($id);
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
        $count = Order::count();
        return $count;
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
        $orders = Order::where('orders.rider_id', $rider_id)
            ->where('status', 'success')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->select('orders.*', 'shops.name as shop_name');
        
        if($start_date != 'null' && $end_date != 'null') {
            $orders = $orders->whereBetween('orders.schedule_date', [$start_date, $end_date]);
        } else {
            $orders = $orders->whereDate('orders.schedule_date', $currentDate);
        }
        $orders = $orders->offset($offset)->limit($limit)->orderBy('orders.id','DESC')->get();
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
        $order = Order::where('orders.id', $id)
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
        if (Storage::exists('order_data.txt')) {
            $orderDataJson = Storage::get('order_data.txt');
            $orders = json_decode($orderDataJson, true);
        }
        $order_code = $order->order_code;
        $order['delivered_at'] = null;
        if (isset($orders[$order_code])) {
            $orderData = $orders[$order_code];
            if(isset($orderData['delivered_at'])){
                $order['delivered_at'] = $orderData['delivered_at'];
            }
        }
        // dd($order);

        return $order;
    }

    public function trackOrderByOrderID($id)
    {
        $order = Order::where('orders.order_code', $id)
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->select('orders.*', 'shops.name as shop_name', 'riders.name as rider_name', 'riders.phone_number as rider_phone_number')
            ->first();
        return $order;
    }

    public function getAllOrderIdsByShopID($id)
    {
        $orders = Order::where('shop_id', $id)->pluck('id')->toArray();
        return $orders;
    }

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
        $query = Order::where('orders.status', 'in-warehouse')
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

    public function getTotalCreditForShop($shop_id)
    {
        $total_credit = Order::where('shop_id', $shop_id)
            ->where('status','success')
            ->where('payment_method', 'cash_on_delivery')
            ->sum('total_amount');
        return $total_credit;
    }

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
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name', 'item_types.name as item_type_name', 'delivery_types.name as delivery_type_name', 'delivery_types.notified_on as notified_on');
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
            ->select('shop_id','customer_name', 'customer_phone_number', 'rider_id')
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
}
