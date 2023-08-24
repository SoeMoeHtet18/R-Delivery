<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\CustomerCollection;
use App\Models\Deficit;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Township;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RiderRepository
{
    public function getAllRidersQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = Rider::select('*')->where('branch_id', $branch_id);
        return $query;
    }

    public function getAllRiders()
    {
        $branch_id = auth()->user()->branch_id;
        $riders = Rider::where('branch_id', $branch_id)->orderBy('name','asc')->get();
        return $riders;
    }

    public function getRiderByID($id)
    {
        $rider = Rider::findOrFail($id);
        return $rider;
    }

    public function getTodayOrderListByRiderID($id)
    {
        $today = Carbon::today()->format('Y-m-d');
        $query = Order::where('orders.rider_id',$id)
            ->whereDate('orders.schedule_date', $today)
            ->where('orders.status','delivering')
            ->leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name', 'item_types.name as item_type_name', 'delivery_types.name as delivery_type_name');
        return $query;
    }

    public function getOrderHistoryListByRiderID($id)
    {
        $query = Order::leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->leftJoin('delivery_types', 'delivery_types.id', 'orders.delivery_type_id')
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name', 'item_types.name as item_type_name', 'delivery_types.name as delivery_type_name')->where('orders.rider_id',$id)->where('status','success');
        return $query;
    }

    public function getOrderList($id)
    {
        $orders = Order::leftJoin('townships', 'townships.id', 'orders.township_id')
            ->leftJoin('riders', 'riders.id', 'orders.rider_id')
            ->leftJoin('shops', 'shops.id', 'orders.shop_id')
            ->leftJoin('users', 'users.id', 'orders.last_updated_by')
            ->leftJoin('cities', 'cities.id', 'orders.city_id')
            ->leftJoin('item_types', 'item_types.id', 'orders.item_type_id')
            ->select('orders.*', 'townships.name as township_name', 'shops.name as shop_name', 'riders.name as rider_name', 'users.name as last_updated_by_name', 'cities.name as city_name', 'item_types.name as item_type_name')->where('orders.rider_id',$id)->orderBy('orders.id','DESC')->get();
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

    public function getRiderBySalaryType($type)
    {
        $branch_id = auth()->user()->branch_id;
        $riders = Rider::where('salary_type',$type)->where('branch_id', $branch_id)->get();
        return $riders;
    }
    
    // public function getTotalSalaryByRider($rider,$daily,$monthly)
    // {
    //     $rider_fees = $rider->townships->first()->pivot->rider_fees;
    //     if($rider->salary_type == 'daily') {
    //         $currentDate = Carbon::parse($daily)->format('Y-m-d');
    //         $total_deli_count = Order::where('rider_id',$rider->id)->where('status','success')->whereDate('schedule_date', $currentDate)->count();
    //         $collection_groups = CollectionGroup::where('rider_id',$rider->id)->whereDate('assigned_date', $currentDate)->get();
    //         $collection_count = 0;
    //         $customer_collection_count = 0;
    //         foreach($collection_groups as $collection_group){
    //             $collection_count += Collection::where('collection_group_id',$collection_group->id)->where('status','complete')->count(); 
    //             $customer_collection_count += CustomerCollection::where('collection_group_id',$collection_group->id)->where('status','complete')->count(); 
    //         }
    //         $total_pick_up_count = $collection_count + $customer_collection_count;
            
    //         $deficit_fees = Deficit::where('rider_id',$rider->id)->whereDate('created_at',$currentDate)->sum('total_amount'); 
    //     } else {
    //         $startDate = Carbon::parse($monthly)->startOfMonth()->format('Y-m-d');
    //         $endDate = Carbon::parse($monthly)->endOfMonth()->format('Y-m-d');
    //         $total_deli_count = Order::where('rider_id', $rider->id)->where('status','success')->whereBetween('schedule_date', [$startDate, $endDate])->count();
    //         $collection_groups = CollectionGroup::where('rider_id',$rider->id)->whereBetween('assigned_date', [$startDate, $endDate])->get();
    //         $collection_count = 0;
    //         $customer_collection_count = 0;
    //         foreach($collection_groups as $collection_group){
    //             $collection_count += Collection::where('collection_group_id',$collection_group->id)->where('status','complete')->count(); 
    //             $customer_collection_count += CustomerCollection::where('collection_group_id',$collection_group->id)->where('status','complete')->count(); 
    //         }
    //         $total_pick_up_count = $collection_count + $customer_collection_count;
    //         $deficit_fees = Deficit::where('rider_id',$rider->id)->whereBetween('created_at',[$startDate, $endDate])->sum('total_amount');
    //     }
    //     $total_pick_up_fees = $rider_fees * $total_pick_up_count;
    //     $total_deli_fees = $rider_fees * $total_deli_count;
    //     $total_salary    = ($total_pick_up_fees + $total_deli_fees + $rider->base_salary) - $deficit_fees;
    //     $data = [];
    //     $data['total_salary'] = $total_salary;
    //     $data['deficit_fees'] = $deficit_fees;
    //     $data['total_pick_up_count'] = $total_pick_up_count;
    //     $data['total_deli_count'] = $total_deli_count;
    //     $data['salary_type'] = $rider->salary_type;
    //     $data['base_salary'] = $rider->base_salary ?? 0;
    //     return $data;
    // }

    public function getTotalSalaryByRider($rider,$daily,$monthly)
    {
        $startDate = $rider->salary_type == 'daily' ?
                Carbon::parse($daily)->format('Y-m-d') : Carbon::parse($monthly)->startOfMonth()->format('Y-m-d');
        $endDate = $rider->salary_type == 'daily' ?
                Carbon::parse($daily)->format('Y-m-d').' 23:59:59' :
                Carbon::parse($monthly)->endOfMonth()->format('Y-m-d').' 23:59:59';

        $deliveredOrders = $this->getDeliveredOrders($rider, $startDate, $endDate);
        $orderCount = $deliveredOrders->count();
        $deliFees = $this->calculateFees($deliveredOrders, $rider->id);

        $collections = $this->getCollections($rider, $startDate, $endDate);
        $totalPickUpFees = $this->calculatePickUpFees($collections, $rider->id);
        $customerExchanges = $this->getCustomerExchanges($rider, $startDate, $endDate);
        $totalCustomerExchangesFees = $this->calculateFees($customerExchanges, $rider->id);
        $totalPickUpCount = $collections->count() + $customerExchanges->count();
        $totalCollectionFees = $totalPickUpFees + $totalCustomerExchangesFees;

        $deficitFees = $this->getDeficitFees($rider, $startDate, $endDate);

        $totalSalary = ($totalCollectionFees + $deliFees + $rider->base_salary) - $deficitFees;

        $data['total_salary'] = $totalSalary;
        $data['deficit_fees'] = $deficitFees;
        $data['total_pick_up_count'] = $totalPickUpCount;
        $data['total_deli_count'] = $orderCount;
        $data['salary_type'] = $rider->salary_type;
        $data['base_salary'] = $rider->base_salary ?? 0;
        return $data;
    }

    private function calculatePickUpFees($collections, $riderId)
    {
        $totalFees = 0;
        foreach($collections as $collection) {
            $riderFee = DB::table('rider_township')
                ->where(['rider_id' => $riderId, 'township_id' => $collection->shop->township_id])
                ->first()->rider_fees ?? 0;

            $totalFees += $riderFee;
        }
        return $totalFees;
    }

    private function calculateFees($items, $riderId)
    {
        $totalFees = 0;

        foreach ($items as $item) {
            $riderFee = DB::table('rider_township')
                ->where(['rider_id' => $riderId, 'township_id' => $item->township_id])
                ->first()->rider_fees;

            $totalFees += $riderFee;
        }

        return $totalFees;
    }

    private function getDeliveredOrders($rider, $startDate, $endDate)
    {
        return Order::where('rider_id', $rider->id)
            ->where('status', 'success')
            ->whereBetween('schedule_date', [$startDate, $endDate])
            ->get();
    }

    private function getCollections($rider, $startDate, $endDate)
    {
        return Collection::whereBetween('collected_at', [$startDate, $endDate])
            ->where('rider_id', $rider->id)
            ->get();
    }

    private function getCustomerExchanges($rider, $startDate, $endDate)
    {
        return CustomerCollection::whereBetween('complete_at', [$startDate, $endDate])
            ->where(['rider_id' => $rider->id, 'is_way_fees_payable' => true])
            ->get();
    }

    private function getDeficitFees($rider, $startDate, $endDate)
    {
        return Deficit::where('rider_id', $rider->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
    }

    public function getRiderByTownship($township_id)
    {
        $branch_id = auth()->user()->branch_id;
        $township = Township::findOrFail($township_id);
        $riders = $township->riders;
        $data = [];
        foreach($riders as $rider) {
            if($rider->branch_id == $branch_id){
                $data[] = $rider;
            }
        }
        return $data;
    }
}