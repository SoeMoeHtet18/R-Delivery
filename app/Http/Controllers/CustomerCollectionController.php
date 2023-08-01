<?php

namespace App\Http\Controllers;

use App\Models\CustomerCollection;
use App\Repositories\CustomerCollectionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ShopRepository;
use App\Services\CustomerCollectionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerCollectionController extends Controller
{
    protected $customerCollectionRepository;
    protected $customerCollectionService;
    protected $orderRepository;
    protected $shopRepository;

    public function __construct(CustomerCollectionRepository $customerCollectionRepository,
     CustomerCollectionService $customerCollectionService,
      OrderRepository $orderRepository,
      ShopRepository $shopRepository,
      )
    {
        $this->customerCollectionRepository = $customerCollectionRepository;
        $this->customerCollectionService = $customerCollectionService;
        $this->orderRepository = $orderRepository;
        $this->shopRepository = $shopRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = $this->shopRepository->getAllShops();
        return view('admin.customer-collection.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $order_id = $request->order_id;
        $order = $this->orderRepository->getOrderByID($order_id);
        return view('admin.customer-collection.create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        return view('admin.customer-collection.edit', compact('customer_collection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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
        $data = $this->customerCollectionRepository->getAllCustomerCollections($request);

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
            ->make(true);
    }
}
