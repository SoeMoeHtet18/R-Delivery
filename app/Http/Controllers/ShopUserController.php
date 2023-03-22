<?php

namespace App\Http\Controllers;

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
        if ($request->ajax()) {
            $shop_users = $this->shopUserRepository->getAllShopUsers();
            return DataTables::of($shop_users)
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
        return view('admin.shopuser.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = $this->shopRepository->getAllShops();

        return view('admin.shopuser.create', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $rules = [
            'name'              => 'required|string',
            'phone_number'      => 'required|string|unique:shop_users',
            'email'             => 'unique:shop_users',
            'password'          => 'required|min:8',
        ];

        $customErr = [
            'name.required'              => 'Name field is required',
            'phone_number.required'      => 'Phone Number is required',
            'phone_number.unique'        => 'Phone Number already exists',
            'email'                      => 'Email already exists',
            'password.required'          => 'Password is required',
            'password.min'               => 'Password should be a minimum of 8 characters.',
        ];
        
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();

            $this->shopUserService->saveShopUserData($data);
        }

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

        $shops = $this->shopRepository->getAllShops()->orderByDESC('id')->get();

        return view('admin.shopuser.edit',compact('shop_user','shops'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [ 
            'name'                  => 'required|string',
            'phone_number'          => 'required|string|unique:shop_users,phone_number,'. $id,
            'email'                 => 'unique:shop_users,email,'. $id,
        ];
                
        $customErr = [
            'name.required'                 => 'Name field is required.',
            'phone_number.required'         => 'Phone Number is required.',
            'phone_number.unique'           => 'Phone Number already exists.',
            'email.unique'                  => 'Email already exists.',
        ];
            
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $shop_user = $this->shopUserRepository->getShopUserByID($id);

            $data = $request->all();
            $this->shopUserService->updateShopUserByID($data, $shop_user);
        }
    
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
}
