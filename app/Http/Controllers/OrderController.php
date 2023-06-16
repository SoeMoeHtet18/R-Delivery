<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\RiderAssignRequest;
use App\Repositories\CityRepository;
use App\Repositories\ItemTypeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ShopRepository;
use App\Repositories\TownshipRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected $shopRepository;
    protected $riderRepository;
    protected $cityRepository;
    protected $itemTypeRepository;
    protected $orderRepository;
    protected $townshipRepository;
    protected $orderService;

    public function __construct(
        ShopRepository $shopRepository,
        RiderRepository $riderRepository,
        CityRepository $cityRepository,
        ItemTypeRepository $itemTypeRepository,
        TownshipRepository $townshipRepository,
        OrderRepository $orderRepository,
        OrderService $orderService
    ) {
        $this->shopRepository = $shopRepository;
        $this->riderRepository = $riderRepository;
        $this->cityRepository = $cityRepository;
        $this->itemTypeRepository = $itemTypeRepository;
        $this->townshipRepository = $townshipRepository;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cities = $this->cityRepository->getAllCities();
        $townships = $this->townshipRepository->getAllTownships();
        $riders = $this->riderRepository->getAllRiders();
        $shops  = $this->shopRepository->getAllShops();

        return view('admin.order.index', compact('cities', 'townships', 'riders', 'shops'));
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
        $townships = $this->townshipRepository->getAllTownships();
        $townships = $townships->sortByDesc('id');
        $item_types = $this->itemTypeRepository->getAllItemTypes();
        $item_types = $item_types->sortByDesc('id');

        return view('admin.order.create', compact('shops', 'riders', 'cities', 'item_types', 'townships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderCreateRequest $request)
    {
        $data = $request->all();
        $this->orderService->saveOrderData($data);
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = $this->orderRepository->getOrderByID($id);
        return view('admin.order.detail', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = $this->orderRepository->getOrderByID($id);
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        $item_types = $this->itemTypeRepository->getAllItemTypes();
        $item_types = $item_types->sortByDesc('id');

        $city_id = $order->city_id;
        $townships = $this->townshipRepository->getAllTownshipsByCityID($city_id);
        $townships = $townships->sortByDesc('id');

        $township = $this->townshipRepository->getTownshipById($order->township_id);
        $riders = $township->riders;
        $riders = $riders->sortByDesc('id');

        $date = new Carbon($order->schedule_date);
        $scheduledate = $date->format('Y-m-d');

        return view('admin.order.edit', compact('order', 'shops', 'riders', 'townships', 'cities', 'item_types', 'scheduledate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, string $id)
    {
        $order = $this->orderRepository->getOrderByID($id);
        $data = $request->all();
        $file = $request->file('proof_of_payment');
        $this->orderService->updateOrderByID($data, $order, $file);

        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->orderService->deleteOrderByID($id);
        return redirect()->route('orders.index');
    }

    public function getPendingOrdersTableByRiderID(Request $request, $id)
    {
        $data = $this->riderRepository->getPendingOrderListByRiderID($id);
        return DataTables::of($data)
            ->addColumn('order_code', function ($data) {
                return '<a href="' . route("orders.show", $data->id) . '">' . $data->order_code . '</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['order_code'])
            ->orderColumn('orders.id', '-id $1')
            ->make(true);
    }

    public function getOrderHistoryTableByRiderID(Request $request, $id)
    {
        $data = $this->riderRepository->getOrderHistoryListByRiderID($id);
        return DataTables::of($data)
            ->addColumn('order_code', function ($data) {
                return '<a href="' . route("orders.show", $data->id) . '">' . $data->order_code . '</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['order_code'])
            ->orderColumn('orders.id', '-id $1')
            ->make(true);
    }

    public function getShopOrdersTable(Request $request, $id)
    {
        $data = $this->shopRepository->getShopOrdersByShopID($id);
        return DataTables::of($data)
            ->addColumn('order_code', function ($data) {
                return '<a href="' . route("orders.show", $data->id) . '">' . $data->order_code . '</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['order_code'])
            ->orderColumn('orders.id', '-id $1')
            ->make(true);
    }

    public function assignRider($id)
    {
        $order = $this->orderRepository->getOrderByID($id);
        $township = $this->townshipRepository->getTownshipById($order->township_id);
        $riders = $township->riders;
        return view('admin.order.assign_rider', compact('order', 'township', 'riders'));
    }

    public function assignRiderToOrder(RiderAssignRequest $request, $id)
    {
        $order = $this->orderRepository->getOrderByID($id);
        $data = $request->all();
        $order = $this->orderService->assignRider($order, $data);

        return redirect()->route('orders.index');
    }

    public function getAjaxOrderData(Request $request)
    {
        $status = $request->status;
        $township = $request->township;
        $search = $request->search;
        $city = $request->city;
        $rider = $request->rider;
        $shop  = $request->shop;
        $data = $this->orderRepository->getAllOrdersQuery();
        if ($status != null) {
            $data = $data->where('orders.status', $status);
        }
        if ($township != null) {
            $data = $data->where('orders.township_id', $township);
        }
        if ($search) {
            $data = $data->where('orders.order_code', 'like', '%' . $search . '%')->orWhere('orders.customer_name', 'like', '%' . $search . '%')->orWhere('orders.customer_phone_number', 'like', '%' . $search . '%')->orWhere('orders.item_type', 'like', '%' . $search . '%')->orWhere('orders.full_address', 'like', '%' . $search . '%');
        }
        if ($city != null) {
            $data = $data->where('orders.city_id', $city);
        }
        if ($rider != null) {
            $data = $data->where('orders.rider_id', $rider);
        }
        if ($shop != null) {
            $data = $data->where('orders.shop_id', $shop);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('order_code', function ($data) {
                return '<a href="' . route("orders.show", $data->id) . '">' . $data->order_code . '</a>';
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '
                    <a href="' . route("orders.show", $row->id) . '" class="btn btn-info btn-sm">View</a> 
                    <a href="' . route("orders.edit", $row->id) . '" class="btn btn-light btn-sm">Edit</a> 
                    <form action="' . route("orders.destroy", $row->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this order?`);">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                    </form>';
                return $actionBtn;
            })
            ->addColumn('first_column', function ($row) {
                $checkbox = '<input class="order-payment" type="checkbox" 
                data-id="' . $row->id . '" data-shop_id="' . $row->shop_id . '"
                data-total_amount="' . $row->total_amount . '" 
                data-markup_delivery_fees="' . $row->markup_delivery_fees . '" 
                data-payment_flag="' . $row->payment_flag . '">';
                return $checkbox;
            })
            ->rawColumns(['action', 'order_code', 'first_column'])
            ->orderColumn('id', '-orders.id')
            ->make(true);
    }

    public function getPendingOrderTableByTownshipID(Request $request, $id)
    {
        if ($request->ajax()) {
            $orderQuery = $this->orderRepository->getAllOrdersQuery();

            $orders = $orderQuery->where('township_id', $id)->where('status', 'pending')->orWhere('status', 'delay');

            return DataTables::of($orders)
                ->addColumn('order_code', function ($data) {
                    return '<a href="' . route("orders.show", $data->id) . '">' . $data->order_code . '</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['order_code'])
                ->orderColumn('orders.id', '-id $1')
                ->make(true);
        }
    }

    public function getCompletedOrderTableByTownshipID(Request $request, $id)
    {
        if ($request->ajax()) {
            $orderQuery = $this->orderRepository->getAllOrdersQuery();

            $orders = $orderQuery->where('township_id', $id)->where('status', 'success');

            return DataTables::of($orders)
                ->addColumn('order_code', function ($data) {
                    return '<a href="' . route("orders.show", $data->id) . '">' . $data->order_code . '</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['order_code'])
                ->orderColumn('orders.id', '-id $1')
                ->make(true);
        }
    }

    public function getCanceledOrderTableByTownshipID(Request $request, $id)
    {
        if ($request->ajax()) {
            $orderQuery = $this->orderRepository->getAllOrdersQuery();

            $orders = $orderQuery->where('township_id', $id)->where('status', 'cancel');

            return DataTables::of($orders)
                ->addColumn('order_code', function ($data) {
                    return '<a href="' . route("orders.show", $data->id) . '">' . $data->order_code . '</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['order_code'])
                ->orderColumn('orders.id', '-id $1')
                ->make(true);
        }
    }

    public function createByShopID(Request $request)
    {
        $shop_id = $request->input('shop_id'); // Retrieve shop_id from the request
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        $townships = $this->townshipRepository->getAllTownships();
        $townships = $townships->sortByDesc('id');
        $item_types = $this->itemTypeRepository->getAllItemTypes();
        $item_types = $item_types->sortByDesc('id');

        return view('admin.order.create', compact('shop_id', 'shops', 'riders', 'cities', 'item_types', 'townships'));
    }

    public function showTracking(Request $request)
    {
        $order_id = $request->order_id;
        if (strpos($order_id, '#') !== false) {
            $order_id = str_replace('#', '', $order_id);
        }
        $order = $this->orderRepository->trackOrderByOrderID($order_id);
        $orderId = $order_id;
        $orders = [];
        if (Storage::exists('order_data.txt')) {
            $orderDataJson = Storage::get('order_data.txt');
            $orders = json_decode($orderDataJson, true);
        }
        if (isset($orders[$orderId])) {
            $orderData = $orders[$orderId];
            $order->order_data = $orderData;
        }
        return view('tracking',compact('order'));
    }
}
