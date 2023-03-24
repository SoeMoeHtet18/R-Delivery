<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopUserCreateApiRequest;
use App\Http\Requests\ShopUserCreateRequest;
use App\Http\Requests\ShopUserLoginRequest;
use App\Http\Requests\ShopUserUpdateApiRequest;
use App\Models\ShopUser;
use App\Repositories\OrderRepository;
use App\Repositories\ShopRepository;
use App\Repositories\ShopUserRepository;
use App\Services\OrderService;
use App\Services\ShopUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopUserApiController extends Controller
{
    protected $shopUserRepository;
    protected $shopRepository;
    protected $orderRepository;
    protected $orderService;
    protected $shopUserService;

    public function __construct(ShopUserRepository $shopUserRepository,ShopRepository $shopRepository,OrderService $orderService,ShopUserService $shopUserService, OrderRepository $orderRepository)
    {
        $this->shopUserRepository = $shopUserRepository;
        $this->shopRepository = $shopRepository;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->shopUserService = $shopUserService;
    }

    public function shopUsersLoginApi(ShopUserLoginRequest $request)
    {
        
        if(Auth::guard('shopuser')->attempt(['phone_number' => request('phone_number'), 'password' => request('password')])){ 
            $shopuser = ShopUser::where('id', Auth::guard('shopuser')->user()->id)->first();
            $shopuser->token =  $shopuser->createToken('ShopUser')->accessToken;
            $shopuser->refresh_token =  $shopuser->createToken('ShopUser')->accessToken;
            ShopUser::where('phone_number',request('phone_number'))->update(['token' => $shopuser->token,'refresh_token' => $shopuser->refresh_token]);       
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
        $orders = $this->orderService->saveOrderByShopID($data, $shop_user->shop_id);
        return response()->json( ['data' => $orders, 'message' => 'Successfully Created Order By Shop Owner', 'status' => 'success'], 200); 
    }

    public function create(ShopUserCreateApiRequest $request)
    {
        
        $data = $request->all();
        $shop_user = $this->shopUserService->saveShopUserData($data);
        $shop_user->token =  $shop_user->createToken('ShopUser')->accessToken;
        $shop_user->refresh_token =  $shop_user->createToken('ShopUser')->accessToken;
        ShopUser::where('id',$shop_user->id)->update(['token' => $shop_user->token,'refresh_token' => $shop_user->refresh_token]);       

        return response()->json( ['data' => $shop_user, 'message' => 'Successfully Create Shop User', 'status' => 'success'], 200); 
        
    }

    public function update(ShopUserUpdateApiRequest $request,string $id)
    {
        
            $data = $request->all();
            $shop_user = $this->shopUserRepository->getShopUserByID($id);  
            $data = $this->shopUserService->updateShopUserByID($data, $shop_user);  

            return response()->json( ['data' => $data, 'message' => 'Successfully Update Shop User', 'status' => 'success'], 200); 
        
    }

    public function delete(Request $request)
    {
        $shop_user_id = $request->shop_user_id;
        $this->shopUserService->deleteShopUserByID($shop_user_id);
        return response()->json(['message' => 'Successfully Delete Shop User', 'status' => 'success'], 200); 
    }
    public function changeOrderStatus(Request $request)
    {
        $order_id = $request->order_id;
        $status = $request->status;
        $order = $this->orderRepository->getOrderByID($order_id);
        $this->orderService->changeStatus($order,$status);
        return response()->json(['data' => [], 'message' => 'Successfull Change Order Status', 'status' => 'success',200]);
    }
}
