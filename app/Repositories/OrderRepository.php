<?php

namespace App\Repositories;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function getOrderByID($id)
    {
        $order = Order::findOrFail($id);
        return $order;
    }
    public function getAllOrders()
    {
        $order = Order::all();
        return $order;
    }
    public function getAllOrdersQuery()
    {
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name', 'item_types.name as item_type_name');
        return $query;
    }

    public function getOrderByCustomerPhoneNumber($phone_number)
    {
        $data = Order::where('customer_phone_number', $phone_number)->first();
        return $data;
    }

    public function getOrdersByShopID($id, $status)
    {
        if ($status == 'success') {
            $order = Order::where('shop_id', $id)->where('status', 'success')
                ->orderBy('id', 'desc')
                ->get();
        } else if ($status == 'canceled') {
            $order = Order::where('shop_id', $id)->where('status', 'cancel')
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            $order = Order::where('shop_id', $id)->where('status', 'pending')->orWhere('status', 'delay')->orWhere('status', 'cancel_request')
                ->orderBy('id', 'desc')
                ->get();
        }
        return $order;
    }

    public function getAllOrdersCount()
    {
        $count = Order::count();
        return $count;
    }

    public function getOrdersStatusCountByShopID($shop_id)
    {
        $status = ['pending', 'success', 'delay', 'cancel', 'cancel_request'];
        $orders = Order::where('shop_id', $shop_id)
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
            ->whereIn('orders.status', ['pending', 'delay'])
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
            ->whereIn('orders.status', ['pending', 'delay'])
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
            ->where('status', 'delivered')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->select('orders.*', 'shops.name as shop_name');
        
        if($start_date != 'null' && $end_date != 'null') {
            $orders = $orders->whereBetween('orders.created_at', [$start_date, $end_date]);
        } else {
            $orders = $orders->whereDate('orders.created_at', $currentDate);
        }
        $orders = $orders->offset($offset)->limit($limit)->orderBy('orders.id','DESC')->get();
        return $orders;
    }


    public function getOrderListCount($rider_id)
    {
        $status = ['one day', 'upcoming', 'history'];
        $today = Carbon::today();

        $one_day = Order::where('rider_id', $rider_id)
            ->whereDate('schedule_date', $today)
            ->whereIn('orders.status', ['pending', 'delay'])
            ->select('status')
            ->count();

        $upcoming = Order::where('rider_id', $rider_id)
            ->whereDate('schedule_date', '>', $today)
            ->whereIn('orders.status', ['pending', 'delay'])
            ->select('status')
            ->count();

        $history = Order::where('rider_id', $rider_id)
            ->where('status', 'success')
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
            ->select('orders.*', 'shops.name as shop_name')
            ->first();

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
        $query = Order::where('orders.status', 'cancel_request')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->select('orders.*',  'shops.name as shop_name', 'riders.name as rider_name');
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
}
