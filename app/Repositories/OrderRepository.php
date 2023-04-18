<?php

namespace App\Repositories;

use App\Models\Order;
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
        $query = Order::leftJoin('townships','townships.id','orders.township_id')->leftJoin('riders','riders.id','orders.rider_id')->leftJoin('shops','shops.id','orders.shop_id')->leftJoin('users','users.id','orders.last_updated_by')->leftJoin('cities','cities.id','orders.city_id')->select('orders.*','townships.name as township_name','shops.name as shop_name','riders.name as rider_name','users.name as last_updated_by_name','cities.name as city_name');
        return $query;
    }

    public function getOrderByCustomerPhoneNumber($phone_number)
    {
        $data = Order::where('customer_phone_number',$phone_number)->first();
        return $data;
    }
    
    public function getOrdersByShopID($id)
    {
        $order = Order::where('shop_id',$id)->first();
        return $order;
    }

    public function getAllOrdersCount()
    {
        $count = Order::count();
        return $count;
    }

    public function getOrdersStatusCountByShopID($shop_id)
    {
        $status = ['pending','success','delay','cancel'];
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
        $status = ['pending','success','delay','cancel'];
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
}