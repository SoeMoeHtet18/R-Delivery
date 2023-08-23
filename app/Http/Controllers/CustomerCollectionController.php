<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerCollectionRequest;
use App\Models\CustomerCollection;
use App\Repositories\CityRepository;
use App\Repositories\CollectionGroupRepository;
use App\Repositories\CustomerCollectionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ShopRepository;
use App\Repositories\TownshipRepository;
use App\Services\CustomerCollectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerCollectionController extends Controller
{
    protected $customerCollectionRepository;
    protected $customerCollectionService;
    protected $orderRepository;
    protected $shopRepository;
    protected $collectionGroupRepository;
    protected $riderRepository;
    protected $cityRepository;
    protected $townshipRepository;

    public function __construct(
        CustomerCollectionRepository $customerCollectionRepository,
        CustomerCollectionService $customerCollectionService,
        OrderRepository $orderRepository,
        ShopRepository $shopRepository,
        CollectionGroupRepository $collectionGroupRepository,
        RiderRepository $riderRepository,
        CityRepository $cityRepository,
        TownshipRepository $townshipRepository
    ) {
        $this->customerCollectionRepository = $customerCollectionRepository;
        $this->customerCollectionService = $customerCollectionService;
        $this->orderRepository = $orderRepository;
        $this->shopRepository = $shopRepository;
        $this->collectionGroupRepository = $collectionGroupRepository;
        $this->riderRepository = $riderRepository;
        $this->cityRepository  = $cityRepository;
        $this->townshipRepository = $townshipRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = $this->shopRepository->getAllShops();
        $riders = $this->riderRepository->getAllRiders();
        return view('admin.customer-collection.index', compact('shops', 'riders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $order_id = $request->order_id;
        $shops = $this->shopRepository->getAllShops();
        $riders = $this->riderRepository->getAllRiders();
        $cities = $this->cityRepository->getAllCities();
        $townships = $this->townshipRepository->getAllTownships();

        if ($order_id) {
            $order = $this->orderRepository->getOrderByID($order_id);
            return view('admin.customer-collection.create', compact('order'));
        } else {
            $orders = $this->orderRepository->getAllOrders();
            $orders = $orders->sortByDesc('id');
            return view('admin.customer-collection.create', compact('orders', 'shops', 'riders', 'cities', 'townships'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerCollectionRequest $request)
    {
        $data = $request->all();
        $this->customerCollectionService->createCustomerCollection($data);
        return redirect()->route('customer-collections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer_collection = $this->customerCollectionRepository->getCustomerCollectionById($id);
        return view('admin.customer-collection.detail', compact('customer_collection'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer_collection = $this->customerCollectionRepository->getCustomerCollectionById($id);
        $collection_groups = $this->collectionGroupRepository->getAllCollectionGroups();
        $collection_groups = $collection_groups->sortByDesc('id');
        $orders = $this->orderRepository->getAllOrders();
        $orders = $orders->sortByDesc('id');
        $shops = $this->shopRepository->getAllShops();
        $riders = $this->riderRepository->getAllRiders();
        $cities = $this->cityRepository->getAllCities();
        $townships = $this->townshipRepository->getAllTownships();
        $date = new Carbon($customer_collection->assigned_at);
        $scheduledate = $date->format('Y-m-d');
        return view('admin.customer-collection.edit', compact('customer_collection', 'collection_groups', 
            'orders', 'shops', 'riders', 'cities', 'townships', 'scheduledate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerCollectionRequest $request, $id)
    {
        $data = $request->all();
        $this->customerCollectionService->updateCustomerCollection($data, $id);
        return redirect()->route('customer-collections.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->customerCollectionService->deleteCustomerCollectionByID($id);
        return redirect()->route('customer-collections.index');
    }

    public function getAjaxCustomerCollections(Request $request)
    {
        $request = $request->all();
        $data = $this->customerCollectionRepository->getAllCustomerCollectionsQueryForTable($request);

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route("customer-collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("customer-collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="' . route("customer-collections.destroy", $row->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this city?`);">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('id', '-customer_collections.id')
            ->make(true);
    }

    public function getAjaxWarehouseCustomerCollections(Request $request)
    {
        $request = $request->all();
        $data = $this->customerCollectionRepository->getWarehouseCustomerCollections($request);

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route("customer-collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("customer-collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="' . route("customer-collections.destroy", $row->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this city?`);">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('id', '-customer_collections.id')
            ->make(true);
    }

    public function getCustomerCollectionsByGroup(Request $request)
    {
        $collection_group_id = $request['collection_group_id'];
        $data = $this->customerCollectionRepository->getAllCustomerCollectionsByGroupId($collection_group_id);

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route("customer-collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("customer-collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="' . route("customer-collections.destroy", $row->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this city?`);">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('id', '-customer_collections.id')
            ->make(true);
    }

    public function getCustomerExchangeByShopID(Request $request, $id)
    {
        $start = $request->from_date;
        $end = $request->to_date . ' 23:59:00';
        $data = $this->customerCollectionRepository->getCustomerCollectionByShopID($id);
        if ($start && $end) {
            $data = $data->whereBetween('customer_collections.created_at', [$start, $end]);
        }
        $customerCollections = $data;
        

        return DataTables::of($customerCollections)
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route("customer-collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("customer-collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="' . route("customer-collections.destroy", $row->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this city?`);">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('id', '-customer_collections.id')
            ->make(true);
    }
}
