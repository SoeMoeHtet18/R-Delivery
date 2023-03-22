<?php

namespace App\Http\Controllers;

use App\Repositories\ShopRepository;
use App\Services\ShopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ShopController extends Controller
{
    protected $shopRepository;
    protected $shopService;

    public function __construct(ShopRepository $shopRepository, ShopService $shopService)
    {
        $this->shopRepository = $shopRepository;
        $this->shopService = $shopService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {

            $shops = $this->shopRepository->getAllShops();

            return DataTables::of($shops)
                ->addIndexColumn()
                ->addColumn('action', function($shops) {
                    $actionBtns =  '
                        <a href="'. route("shops.show", $shops->id) .'" class="btn btn-info btn-sm">View</a>
                        <a href="'. route("shops.edit", $shops->id) .'" class="btn btn-light btn-sm">Edit</a>
                        <form action="'. route("shops.destroy", $shops->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this shop?`);">
                            <input type="hidden" name="_token" value="'. csrf_token() .'"/>
                            <input type="hidden" name="_method" value="DELETE"/>
                            <input type="submit" value="Delete" class="btn btn-danger btn-sm"/>
                        </form>';
                    return $actionBtns;
                })
                ->rawColumns(['action'])
                ->orderColumn('id', '-id $1')
                ->make(true);
        }

        return view('admin.shop.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shop.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string|unique:shops'
        ];

        $customErr = [
            'name.required' => 'Name field is required',
            'address.required' => 'Address field is required',
            'phone_number.required' => 'Phone Number is required',
            'phone_number.unique' => 'Phone Number already exists'
        ];

        $validator = Validator::make($request->all(), $rules, $customErr);
        if  ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();

            $this->shopService->saveShopData($data);
        }
        return redirect(route('shops.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shop = $this->shopRepository->getShopByID($id);

        return view('admin.shop.detail', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shop = $this->shopRepository->getShopByID($id);
        
        return view('admin.shop.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shop = $this->shopRepository->getShopByID($id);

        $rules = [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string|unique:shops,phone_number,' . $id
        ];

        $customErr = [
            'name.required' => 'Name field is required',
            'address.required' => 'Address field is required',
            'phone_number.required' => 'Phone Number is required',
            'phone_number.unique' => 'Phone Number already exists'
        ];

        $validator = Validator::make($request->all(), $rules, $customErr);
        if  ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();

            $this->shopService->updateShopByID($data, $shop);
        }
        return redirect(route('shops.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->shopService->deleteShopByID($id);
        
        return redirect(route('shops.index'));
    }

    public function getShopUsersTable(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = $this->shopRepository->getShopUsersByShopID($id);
            return DataTables::of($data)
            ->addColumn('name', function($data) {
                return '<a href="' . route("shopusers.show", $data->id) . '">' . $data->name . '</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['name'])
            ->orderColumn('id', '-id $1')
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
