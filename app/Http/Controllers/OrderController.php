<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Models\Order;
use App\Repositories\CityRepository;
use App\Repositories\ItemTypeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ShopRepository;
use App\Repositories\TownshipRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected $shopRepository;
    protected $riderRepository;
    protected $cityRepository;
    protected $itemTypeRepository;
    protected $orderRepository;
    protected $townshipRepository;

    public function __construct(ShopRepository $shopRepository, 
        RiderRepository $riderRepository, 
        CityRepository $cityRepository, 
        ItemTypeRepository $itemTypeRepository, 
        OrderRepository $orderRepository,
        TownshipRepository $townshipRepository)
    {
        $this->shopRepository = $shopRepository;
        $this->riderRepository = $riderRepository;
        $this->cityRepository = $cityRepository;
        $this->itemTypeRepository = $itemTypeRepository;
        $this->orderRepository = $orderRepository;
        $this->townshipRepository = $townshipRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->orderRepository->getAllOrdersQuery();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                        <a href="'. route("orders.show", $row->id) .'" class="btn btn-info btn-sm">View</a> 
                        <a href="'. route("orders.edit", $row->id) .'" class="btn btn-light btn-sm">Edit</a> 
                        <form action="'.route("orders.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this order?`);">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->orderColumn('id', '-orders.id')
                ->make(true);
        }
        return view('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        $item_types = $this->itemTypeRepository->getAllItemTypes();
        $item_types = $item_types->sortByDesc('id');
        $order_code = MyHelper::nomenclature(['table_name'=>'orders','prefix'=>'OD','column_name'=>'order_code']);
        
        return view('admin.order.create',compact('shops', 'riders', 'cities', 'item_types', 'order_code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'order_code'            => 'required|unique:orders',
            'customer_name'         => 'required',
            'customer_phone_number' => 'required',
            'city_id'               => 'required',
            'township_id'           => 'required',
            'shop_id'               => 'required',
            'quantity'              => 'required',
            'total_amount'          => 'required',
            'delivery_fees'         => 'required',
            'item_type'             => 'required',
            'type'                  => 'required',
            'collection_method'     => 'required',
        ];

        $customErr = [
            'order_code.required'               => 'Order Code field is required',
            'order_code.unique'                 => 'Order Code already exists',
            'customer_name.required'            => 'Customer Name field is required',
            'customer_phone_number.required'    => 'Customer Phone Number field is required',
            'city_id.required'                  => 'City field is required',
            'township_id.required'              => 'Township field is required',
            'shop_id.required'                  => 'Shop field is required',
            'quantity.required'                 => 'Quantity field is required',
            'total_amount'                      => 'Total Amount field is required',
            'delivery_fees.required'            => 'Delivery Fees is required',
            'item_type.required'                => 'Item Type field is required',
            'type.required'                     => 'Type field is required',
            'collection_method.required'        => 'Collection Method field is required',
            
        ];

        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $order = new Order();
            $order->order_code =  $request->order_code;
            $order->customer_name =  $request->customer_name;
            $order->customer_phone_number =  $request->customer_phone_number;
            $order->city_id =  $request->city_id;
            $order->township_id =  $request->township_id;
            $order->rider_id =  $request->rider_id ?? null;
            $order->shop_id =  $request->shop_id;
            $order->quantity =  $request->quantity;
            $order->delivery_fees =  $request->delivery_fees;
            $order->total_amount = $request->total_amount;
            $order->markup_delivery_fees =  $request->markup_delivery_fees ?? null;
            $order->remark =  $request->remark ?? null;
            $order->status =  $request->status;
            $order->item_type =  $request->item_type;
            $order->full_address =  $request->full_address ?? null;
            $order->schedule_date =  $request->schedule_date ?? null ;
            $order->type =  $request->type;
            $order->collection_method =  $request->collection_method;
            $order->proof_of_payment =  $request->proof_of_payment ?? null;
            $order->save();
        }
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('rider', 'shop', 'township', 'user' )->where('id',$id)->first();
        if(!$order) {
            abort(404);
        }
        return view('admin.order.detail',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = $this->orderRepository->getOrderByID($id);
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        $item_types = $this->itemTypeRepository->getAllItemTypes();
        $item_types = $item_types->sortByDesc('id');
        
        $city_id = $order->city_id;
        $townships = $this->townshipRepository->getAllTownshipsByCityID($city_id);
        $townships = $townships->sortByDesc('id');
        
        $date = new Carbon($order->schedule_date);
        $scheduledate = $date->format('Y-m-d');

        return view('admin.order.edit', compact('order', 'shops', 'riders', 'townships', 'cities', 'scheduledate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $rules = [
            'customer_name'         => 'required',
            'customer_phone_number' => 'required',
            'township_id'           => 'required',
            'shop_id'               => 'required',
            'quantity'              => 'required',
            'total_amount'          => 'required',
            'delivery_fees'         => 'required',
            'item_type'             => 'required',
            'type'                  => 'required',
            'collection_method'     => 'required',
        ];

        $customErr = [
            'customer_name.required'            => 'Customer Name field is required',
            'customer_phone_number.required'    => 'Customer Phone Number field is required',
            'township_id.required'              => 'Township field is required',
            'shop_id.required'                  => 'Shop field is required',
            'quantity.required'                 => 'Quantity field is required',
            'total_amount'                      => 'Total Amount field is required',
            'delivery_fees.required'            => 'Delivery Fees is required',
            'item_type.required'                => 'Item Type field is required',
            'type.required'                     => 'Type field is required',
            'collection_method.required'        => 'Collection Method field is required',
            
        ];

        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $order = Order::where('id',$id)->first();
            $order->customer_name =  $request->customer_name;
            $order->customer_phone_number =  $request->customer_phone_number;
            $order->city_id = $request->city_id;
            $order->township_id =  $request->township_id;
            $order->rider_id =  $request->rider_id ?? null;
            $order->shop_id =  $request->shop_id;
            $order->quantity =  $request->quantity;
            $order->delivery_fees =  $request->delivery_fees;
            $order->total_amount = $request->total_amount;
            $order->markup_delivery_fees =  $request->markup_delivery_fees ?? null;
            $order->remark =  $request->remark ?? null;
            $order->status =  $request->status;
            $order->item_type =  $request->item_type;
            $order->full_address =  $request->full_address ?? null;
            $order->schedule_date =  $request->schedule_date ?? null ;
            $order->type =  $request->type;
            $order->collection_method =  $request->collection_method;
            $order->proof_of_payment =  $request->proof_of_payment ?? null;
            $order->save();
        }
        return redirect()->route('orders.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Order::destroy($id);
        return redirect()->route('orders.index');
    }

    public function getPendingOrdersTableByRiderID(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = $this->riderRepository->getPendingOrderListByRiderID($id);
            return DataTables::of($data)
                ->addColumn('order_code', function($data) {
                    return '<a href="' . route("orders.show", $data->id ) . '">' . $data->order_code . '</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['order_code'])
                ->orderColumn('orders.id', '-id $1')
                ->make(true);
        };
    }
    
    public function getOrderHistoryTableByRiderID(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = $this->riderRepository->getOrderHistoryListByRiderID($id);
            return DataTables::of($data)
                ->addColumn('order_code', function($data) {
                    return '<a href="' . route("orders.show", $data->id ) . '">' . $data->order_code . '</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['order_code'])
                ->orderColumn('orders.id', '-id $1')
                ->make(true);
        };
    }

    public function getShopOrdersTable(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = $this->shopRepository->getShopOrdersByShopID($id);
            return DataTables::of($data)
                ->addColumn('order_code', function($data) {
                    return '<a href="' . route("orders.show", $data->id ) . '">' . $data->order_code . '</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['order_code'])
                ->orderColumn('orders.id', '-id $1')
                ->make(true);
        };
    }
}
