<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopCreateRequest;
use App\Http\Requests\ShopUpdateRequest;
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
    public function store(ShopCreateRequest $request)
    {    
        $data = $request->all();
        $this->shopService->saveShopData($data);
        
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
    public function update(ShopUpdateRequest $request, string $id)
    {
        $shop = $this->shopRepository->getShopByID($id);
        $data = $request->all();
        $this->shopService->updateShopByID($data, $shop);
        
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
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $actionBtn = '
                        <a href="'. route("shopusers.show", $data->id) .'" class="edit btn btn-info btn-sm">View</a> 
                        <a href="'. route("shopusers.edit", $data->id) .'" class="edit btn btn-light btn-sm">Edit</a>
                    ';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-id $1')
            ->make(true);
        };
    }

    public function getShopOrdersTable(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = $this->shopRepository->getShopOrdersByShopID($id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                        <a href="'. route("orders.show", $row->id) .'" class="btn btn-info btn-sm">View</a> 
                        <a href="'. route("orders.edit", $row->id) .'" class="btn btn-light btn-sm">Edit</a> 
                        ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->orderColumn('orders.id', '-id $1')
                ->make(true);
        };
    }
}
