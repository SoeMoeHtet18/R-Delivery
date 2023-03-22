<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShopUser;
use App\Repositories\ShopRepository;
use App\Repositories\ShopUserRepository;
use App\Services\OrdersService;
use App\Services\ShopUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopUserApiController extends Controller
{
    protected $shopUserRepository;
    protected $shopRepository;
    protected $ordersService;
    protected $shopUserService;

    public function __construct(ShopUserRepository $shopUserRepository,ShopRepository $shopRepository,OrdersService $ordersService,ShopUserService $shopUserService)
    {
        $this->shopUserRepository = $shopUserRepository;
        $this->shopRepository = $shopRepository;
        $this->ordersService = $ordersService;
        $this->shopUserService = $shopUserService;

    }

    public function shopUsersLoginApi(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        }
        // dd($request->all());

        if(Auth::guard('shopuser')->attempt(['phone_number' => request('phone_number'), 'password' => request('password')])){ 
            $shopuser = ShopUser::where('id', Auth::guard('shopuser')->user()->id)->first();
            $shopuser->token =  $shopuser->createToken('ShopUser')->accessToken;
            ShopUser::where('phone_number',request('phone_number'))->update(['token' => $shopuser->token]);       
            return response()->json( ['data' => $shopuser, 'message' => 'Successfully Logged In', 'status' => 'success'], 200); 
        }else{
            return response()->json(['data' => [], 'message' => 'Invalid credentials.', 'status' => 'fail'], 401); 
        }
    }

    public function show($id) 
    {
        $shopuser = $this->shopUserRepository->getShopUserByID($id);
        return response()->json( ['data' => $shopuser, 'message' => 'Successfully Get Shop User Detail', 'status' => 'success'], 200); 
    }

    public function orderListByShopOwnerID($id) 
    {   
        $shop_user = $this->shopUserRepository->getShopUserByID($id);
        $order_list = $this->shopRepository->getShopOrdersByShopID($shop_user->shop_id);
        return response()->json( ['data' => $order_list, 'message' => 'Successfully Get Order List', 'status' => 'success'], 200); 
    }

    public function orderCreateByShopOwner(Request $request, $id)
    {   
        $shop_user = $this->shopUserRepository->getShopUserByID($id);
        $data = $request->all();
        $orders = $this->ordersService->saveOrderByShopID($data, $shop_user->shop_id);
        return response()->json( ['data' => $orders, 'message' => 'Successfully Created Order By Shop Owner', 'status' => 'success'], 200); 
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'              => 'required',
            'phone_number'      => 'required|string|unique:shop_users',
            'email'             => 'unique:shop_users',
            'password'          => 'required|min:8',
            'device_id'  => 'required',
            'shop_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        } else {
            $data = $request->all();
            $shop_user = $this->shopUserService->saveShopUserData($data);
            $shop_user->token =  $shop_user->createToken('ShopUser')->accessToken;
            ShopUser::where('id',$shop_user->id)->update(['token' => $shop_user->token]);       

            return response()->json( ['data' => $shop_user, 'message' => 'Successfully Create Shop User', 'status' => 'success'], 200); 
        }
    }

    public function update(Request $request,string $id)
    {
        $validator = Validator::make($request->all(),[
            'name'              => 'required',
            'phone_number'      => 'required|string|unique:shop_users,phone_number,'.$id,
            'email'             => 'unique:shop_users,email,'.$id,
            'shop_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        } else {
            $data = $request->all();
            $shop_user = $this->shopUserRepository->getShopUserByID($id);  
            $data = $this->shopUserService->updateShopUserByID($data, $shop_user);  

            return response()->json( ['data' => $data, 'message' => 'Successfully Update Shop User', 'status' => 'success'], 200); 
        }
    }
}