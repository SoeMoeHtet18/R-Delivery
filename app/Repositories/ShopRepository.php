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

    public function getAllShopsQuery($from_date, $to_date)
    {
        $branch_id = auth()->user()->branch_id;
        if($from_date && $to_date) {
            $from_date = Carbon::parse($from_date)->format('Y-m-d');
            $to_date   = Carbon::parse($to_date)->format('Y-m-d').' 23:59:59';
        } else {
            $from_date = Carbon::now()->startOfMonth()->format('Y-m-d');
            $to_date = Carbon::now()->endOfMonth()->format('Y-m-d').' 23:59:59';
        }
        
        return Shop::with(['orders' => function ($query) use ($from_date,$to_date) {
                $query->where('status', 'success')->whereBetween('created_at', [$from_date, $to_date]);
            }])->select('shops.*','townships.name as township_name')
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

    public function getAllShopData($request, $search, $from_date, $to_date)
    {
        $township_id = $request->township_id;
        $city_id = $request->city_id;
        $shop_id = $request->shop_id;
        
        $branch_id = auth()->user()->branch_id;
        // parse date range if exist
        if($from_date && $to_date) {
            $from_date = Carbon::parse($from_date)->format('Y-m-d');
            $to_date   = Carbon::parse($to_date)->format('Y-m-d').' 23:59:59';
        } else {
            // set for this month if date range not exist
            $from_date = Carbon::now()->startOfMonth()->format('Y-m-d');
            $to_date = Carbon::now()->endOfMonth()->format('Y-m-d').' 23:59:59';
        }

        return Shop::where('branch_id', $branch_id)
            ->when(isset($shop_id), function ($query) use ($shop_id) {
                $query->where('id', $shop_id);
            })
            // filter with search
            ->when(isset($search), function ($query) use ($search) {
                $query->where('name','like', '%' . $search . '%')
                    ->orWhere('address','like', '%' . $search . '%')
                    ->orWhere('phone_number','like', '%' . $search . '%');
            })
            // filter with city and township
            ->when(isset($township_id) || isset($city_id), function ($query) use ($township_id, $city_id) {
                $query->whereHas('township', function ($query) use ($township_id, $city_id) {
                    if ($township_id) {
                        $query->where('id', $township_id);
                    }
                    if ($city_id) {
                        $query->whereHas('city', function ($query) use ($city_id) {
                            $query->where('id', $city_id);
                        });
                    }
                });
            })
            // filter with date range
            ->with(['township.city', 'orders' => function ($query) use ($from_date, $to_date) {
                $query->where('status', 'success')->whereBetween('created_at', [$from_date, $to_date]);
            }])
            ->get();
    }
}
