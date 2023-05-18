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
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')->leftJoin('riders', 'riders.id', 'orders.rider_id')->leftJoin('shops', 'shops.id', 'orders.shop_id')->leftJoin('users', 'users.id', 'orders.last_updated_by')->leftJoin('cities', 'cities.id', 'orders.city_id')->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name');
        return $query;
    }

    public function getOrderByCustomerPhoneNumber($phone_number)
    {
        $data = Order::where('customer_phone_number', $phone_number)->first();
        return $data;
    }

    public function getOrdersByShopID($id)
    {
        $order = Order::where('shop_id', $id)->get();
        return $order;
    }

    public function getAllOrdersCount()
    {
        $count = Order::count();
        return $count;
    }

    public function getOrdersStatusCountByShopID($shop_id)
    {
        $status = ['pending', 'success', 'delay', 'cancel'];
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
        $total_amount = Order::where('shop_id', $shop_id)->sum('total_amount');
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

    public function getUpcomingOrderList($rider_id)
    {
        $today = Carbon::today();
        $orders = Order::where('orders.rider_id', $rider_id)
            ->whereIn('orders.status', ['pending', 'delay'])
            ->whereDate('orders.schedule_date', '>', $today)
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->select('orders.*', 'shops.name as shop_name')
            ->orderBy('updated_at', 'asc')
            ->get();
        return $orders;
    }

    public function getOrderHistoryList($rider_id)
    {
        $orders = Order::where('orders.rider_id', $rider_id)
            ->where('status', 'success')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->select('orders.*', 'shops.name as shop_name')
            ->orderBy('updated_at', 'desc')
            ->get();

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
}
