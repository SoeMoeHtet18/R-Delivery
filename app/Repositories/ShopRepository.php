<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\CustomerCollection;
use App\Models\Order;
use App\Models\Shop;
use App\Models\ShopPayment;
use App\Models\ShopUser;
use App\Models\TransactionsForShop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShopRepository
{
    public function getAllShops()
    {
        $branch_id = auth()->user()->branch_id;
        $shops = Shop::where('branch_id', $branch_id)->orderBy('name','asc')->get();
        return $shops;
    }

    public function getAllShopsQuery()
    {
        $branch_id = auth()->user()->branch_id;
        return Shop::select('shops.*','townships.name as township_name')
            ->leftJoin('townships','townships.id','shops.township_id')
            ->where('branch_id', $branch_id);
    }

    public function getShopByID($id)
    {
        $shop = Shop::findOrFail($id);
        return $shop;
    }

    public function getShopUsersByShopID($id)
    {
        $query = ShopUser::where('shop_id', $id);
        return $query;
    }

    public function getShopOrdersByShopID($id)
    {
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->where('orders.shop_id', $id)
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 
                'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name', 
                'item_types.name as item_type_name', 'delivery_types.name as delivery_type_name');
        return $query;
    }

    public function getAllShopCount()
    {
        $user = auth()->user();
        return Shop::where('branch_id', $user->branch_id)->count();
    }
}
