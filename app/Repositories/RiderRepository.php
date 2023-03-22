<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Rider;
use Illuminate\Support\Facades\Auth;

class RiderRepository
{
    public function getAllRiders()
    {
        $riders = Rider::select('*');
        return $riders;
    }

    public function getRiderByID($id)
    {
        $rider = Rider::findOrFail($id);
        return $rider;
    }

    public function getPendingOrderListByRiderID($id)
    {
        $orders = Order::leftJoin('townships','townships.id','orders.township_id')->leftJoin('riders','riders.id','orders.rider_id')->leftJoin('shops','shops.id','orders.shop_id')->leftJoin('users','users.id','orders.last_updated_by')->select('orders.*','townships.name as township_name','shops.name as shop_name','riders.name as rider_name','users.name as last_updated_by_name')->where('orders.rider_id',$id)->where('status','pending');
        return $orders;
    }

    public function getOrderHistoryListByRiderID($id)
    {
        $orders = Order::leftJoin('townships','townships.id','orders.township_id')->leftJoin('riders','riders.id','orders.rider_id')->leftJoin('shops','shops.id','orders.shop_id')->leftJoin('users','users.id','orders.last_updated_by')->select('orders.*','townships.name as township_name','shops.name as shop_name','riders.name as rider_name','users.name as last_updated_by_name')->where('orders.rider_id',$id)->where('status','success');
        return $orders;
    }

    public function getPendingOrderListForAuthenticatedRider($data)
    {
        $rider_id = auth('rider-api')->user()->id;
        $orders = Order::leftJoin('townships','townships.id','orders.township_id')->leftJoin('riders','riders.id','orders.rider_id')->leftJoin('shops','shops.id','orders.shop_id')->leftJoin('users','users.id','orders.last_updated_by')->select('orders.*','townships.name as township_name','shops.name as shop_name','riders.name as rider_name','users.name as last_updated_by_name')->where('orders.rider_id',$rider_id)->where('orders.status',$data['status'])->orderBy('orders.id','DESC')->get();
        return $orders;
    }

    public function getShopListByRiderID($id)
    {
        $shops = Order::leftJoin('shops','shops.id','orders.shop_id')->select('shops.name as shop_name')->where('orders.rider_id',$id)->orderBy('orders.id','DESC')->get();
        return $shops;
    }
}