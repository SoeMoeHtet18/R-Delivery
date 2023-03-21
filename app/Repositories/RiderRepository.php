<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Rider;

class RiderRepository
{
    public function getAllRiders()
    {
        $riders = Rider::all();
        return $riders;
    }

    public function getAllRidersByDESC()
    {
        $riders = Rider::select('*')->orderBy('id','DESC');
        return $riders;
    }

    public function getRiderByID($id)
    {
        $rider = Rider::findOrFail($id);
        return $rider;
    }

    public function getOrderListByRiderID($id)
    {
        $orders = Order::leftJoin('townships','townships.id','orders.township_id')->leftJoin('riders','riders.id','orders.rider_id')->leftJoin('shops','shops.id','orders.shop_id')->leftJoin('users','users.id','orders.last_updated_by')->select('orders.*','townships.name as township_name','shops.name as shop_name','riders.name as rider_name','users.name as last_updated_by_name')->where('orders.rider_id',$id)->orderBy('orders.id','DESC')->get();
        return $orders;
    }

    public function getShopListByRiderID($id)
    {
        $shops = Order::leftJoin('shops','shops.id','orders.shop_id')->select('shops.name as shop_name')->where('orders.rider_id',$id)->orderBy('orders.id','DESC')->get();
        return $shops;
    }
}