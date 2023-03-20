<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShopUser;
use App\Repositories\ShopRepository;
use App\Repositories\ShopUserRepository;
use App\Services\OrdersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopUserApiController extends Controller
{
    protected $shopUserRepository;
    protected $shopRepository;
    protected $ordersService;

    public function __construct(ShopUserRepository $shopUserRepository,ShopRepository $shopRepository,OrdersService $ordersService)
    {
        $this->shopUserRepository = $shopUserRepository;
        $this->shopRepository = $shopRepository;
        $this->ordersService = $ordersService;

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
}
