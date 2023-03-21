<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Shop;
use App\Models\ShopUser;
use Yajra\DataTables\Facades\DataTables;

class ShopRepository
{   
    public function getAllShops()
    {
        $shops = Shop::select('*');
        return $shops;
    }

    public function getShopByID($id)
    {
        $shop = Shop::findOrFail($id);
        return $shop;
    }

    public function getShopUsersByShopID($id)
    { 
        $data = ShopUser::where('shop_id', $id);
        return $data;
    }

    public function getShopOrdersByShopID($id)
    { 
        $data = Order::leftJoin('townships','townships.id','orders.township_id')->leftJoin('riders','riders.id','orders.rider_id')->leftJoin('shops','shops.id','orders.shop_id')->leftJoin('users','users.id','orders.last_updated_by')->select('orders.*','townships.name as township_name','shops.name as shop_name','riders.name as rider_name','users.name as last_updated_by_name')->where('orders.shop_id',$id);
        return $data;
    }
}