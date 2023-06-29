<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Rider;

class RiderRepository
{
    public function getAllRidersQuery()
    {
        $query = Rider::select('*');
        return $query;
    }

    public function getAllRiders()
    {
        $riders = Rider::all();
        return $riders;
    }

    public function getRiderByID($id)
    {
        $rider = Rider::findOrFail($id);
        return $rider;
    }

    public function getPendingOrderListByRiderID($id)
    {
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')->leftJoin('riders', 'riders.id', 'orders.rider_id')->leftJoin('shops', 'shops.id', 'orders.shop_id')->leftJoin('users', 'users.id', 'orders.last_updated_by')->leftJoin('cities', 'cities.id', 'orders.city_id')->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name')->where('orders.rider_id',$id)->where('status','pending');
        return $query;
    }

    public function getOrderHistoryListByRiderID($id)
    {
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')->leftJoin('riders', 'riders.id', 'orders.rider_id')->leftJoin('shops', 'shops.id', 'orders.shop_id')->leftJoin('users', 'users.id', 'orders.last_updated_by')->leftJoin('cities', 'cities.id', 'orders.city_id')->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name')->where('orders.rider_id',$id)->where('status','success');
        return $query;
    }

    public function getOrderList($id)
    {
        $orders = Order::leftJoin('townships', 'townships.id', 'orders.township_id')->leftJoin('riders', 'riders.id', 'orders.rider_id')->leftJoin('shops', 'shops.id', 'orders.shop_id')->leftJoin('users', 'users.id', 'orders.last_updated_by')->leftJoin('cities', 'cities.id', 'orders.city_id')->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name')->where('orders.rider_id',$id)->orderBy('orders.id','DESC')->get();
        return $orders;
    }

    public function getShopListByRiderID($id)
    {
        $shops = Order::leftJoin('shops','shops.id','orders.shop_id')->select('shops.*')->where('orders.rider_id',$id)->orderBy('orders.id','DESC')->get();
        return $shops;
    }

    public function getAllRidersCount()
    {
        $count = Rider::count();
        return $count;
    }
}