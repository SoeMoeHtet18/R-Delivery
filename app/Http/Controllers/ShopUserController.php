<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopUserCreateRequest;
use App\Http\Requests\ShopUserUpdateRequest;
use App\Repositories\ShopRepository;
use App\Repositories\ShopUserRepository;
use App\Services\ShopUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ShopUserController extends Controller
{
    protected $shopUserRepository;
    protected $shopUserService;
    protected $shopRepository;

    public function __construct(ShopUserRepository $shopUserRepository, ShopUserService $shopUserService, ShopRepository $shopRepository)
    {
        $this->shopUserRepository = $shopUserRepository;
        $this->shopUserService = $shopUserService;
        $this->shopRepository = $shopRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.shopuser.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');

        return view('admin.shopuser.create', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShopUserCreateRequest $request)
    {   
        
        $data = $request->all();
        $this->shopUserService->saveShopUserData($data);
        
        return redirect()->route('shopusers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shop_user = $this->shopUserRepository->getShopUserByID($id);
        
        return view('admin.shopuser.detail',compact('shop_user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shop_user = $this->shopUserRepository->getShopUserByID($id);

        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');

        return view('admin.shopuser.edit',compact('shop_user','shops'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShopUserUpdateRequest $request, string $id)
    {
        $shop_user = $this->shopUserRepository->getShopUserByID($id);
        $data = $request->all();
        $this->shopUserService->updateShopUserByID($data, $shop_user); 
    
        return redirect()->route('shopusers.show', $id);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->shopUserService->deleteShopUserByID($id);
        return redirect()->route('shopusers.index');
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

    public function getAjaxShopUserData(Request $request)
    {
        $shop_user_name = $request->name;
        $phone_number = $request->phone_number;
        $data = $this->shopUserRepository->getAllShopUsersQuery();
        if($shop_user_name != null) {
            $data = $data->where('shop_users.name','like', '%'. $shop_user_name . '%');
        }
        if($phone_number != null) {
            $data = $data->where('shop_users.phone_number', 'like','%'. $phone_number . '%');
        }
            return DataTables::of($data)

                ->addIndexColumn()
                ->addColumn('action', function($shop_users){
                    $actionBtn = '
                            <a href="'. route("shopusers.show", $shop_users->id) .'" class="edit btn btn-info btn-sm">View</a> 
                            <a href="'. route("shopusers.edit", $shop_users->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                            <form action="'.route("shopusers.destroy", $shop_users->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this shop user?`);">
                                <input type="hidden" name="_token" value="'. csrf_token() .'">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                            </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->orderColumn('id', '-id $1')
                ->make(true);
    }
}
