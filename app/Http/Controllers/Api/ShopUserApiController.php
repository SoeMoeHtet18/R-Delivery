<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopUserCreateApiRequest;
use App\Http\Requests\ShopUserCreateRequest;
use App\Http\Requests\ShopUserLoginRequest;
use App\Http\Requests\ShopUserUpdateApiRequest;
use App\Http\Requests\ShopUserUpdateRequest;
use App\Models\ShopUser;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ShopRepository;
use App\Repositories\ShopUserRepository;
use App\Services\NotificationService;
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
    protected $notificationRepository;
    protected $notificationService;

    public function __construct(ShopUserRepository $shopUserRepository, ShopRepository $shopRepository, OrderService $orderService, ShopUserService $shopUserService, OrderRepository $orderRepository, NotificationRepository $notificationRepository, NotificationService $notificationService)
    {
        $this->shopUserRepository = $shopUserRepository;
        $this->shopRepository = $shopRepository;
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        $this->shopUserService = $shopUserService;
        $this->notificationRepository = $notificationRepository;
        $this->notificationService = $notificationService;
    }

    public function shopUsersLoginApi(ShopUserLoginRequest $request)
    {
        if (Auth::guard('shopuser')->attempt(['phone_number' => request('phone_number'), 'password' => request('password')])) {
            $shopuser = ShopUser::where('id', Auth::guard('shopuser')->user()->id)->first();
            $shopuser->token =  $shopuser->createToken('ShopUser')->accessToken;
            $shopuser->refresh_token =  $shopuser->createToken('ShopUser')->accessToken;
            ShopUser::where('phone_number', request('phone_number'))->update(['token' => $shopuser->token, 'refresh_token' => $shopuser->refresh_token]);
            return response()->json(['data' => $shopuser, 'message' => 'Successfully Logged In', 'status' => 'success'], 200);
        } else {
            return response()->json(['data' => [], 'message' => 'Invalid credentials.', 'status' => 'fail'], 401);
        }
    }

    public function show()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop = $shop_user->shop;
        if ($shop) {
            $shop_user['shop_name'] = $shop->name;
            $shop_user['shop_address'] = $shop->address;
        }
        return response()->json(['data' => $shop_user, 'message' => 'Successfully Get Shop User Detail', 'status' => 'success'], 200);
    }

    public function orderListByShopOwnerID(Request $request, $page = 1)
    {
        $status = $request->status;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $shop_user = auth()->guard('shop-user-api')->user();
        $order_list = $this->orderRepository->getOrdersByShopID($shop_user->shop_id, $status, $start_date, $end_date, $page);
        return response()->json(['data' => $order_list, 'message' => 'Successfully Get Order List', 'status' => 'success'], 200);
    }

    public function orderCreateByShopOwner(Request $request)
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $data = $request->all();
        $orders = $this->orderService->saveOrderByShopID($data, $shop_user->shop_id);
        return response()->json(['data' => $orders, 'message' => 'Successfully Created Order By Shop Owner', 'status' => 'success'], 200);
    }

    public function create(ShopUserCreateApiRequest $request)
    {
        $data = $request->all();
        $shop_user = $this->shopUserService->saveShopUserData($data);
        $shop_user->token =  $shop_user->createToken('ShopUser')->accessToken;
        $shop_user->refresh_token =  $shop_user->createToken('ShopUser')->accessToken;
        ShopUser::where('id', $shop_user->id)->update(['token' => $shop_user->token, 'refresh_token' => $shop_user->refresh_token]);

        return response()->json(['data' => $shop_user, 'message' => 'Successfully Create Shop User', 'status' => 'success'], 200);
    }

    public function update(ShopUserUpdateApiRequest $request)
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $data = $request->all();
        $data = $this->shopUserService->updateShopUserByID($data, $shop_user);

        return response()->json(['data' => $data, 'message' => 'Successfully Update Shop User', 'status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $this->shopUserService->deleteShopUserByID($shop_user->id);
        return response()->json(['message' => 'Successfully Delete Shop User', 'status' => 'success'], 200);
    }

    public function changeOrderStatus(Request $request)
    {
        $order_id = $request->order_id;
        $status = $request->status;
        $order = $this->orderRepository->getOrderByID($order_id);
        $data  = $this->orderService->changeStatus($order, $status);
        return response()->json(['data' => $data, 'message' => 'Successfull Change Order Status', 'status' => 'success'], 200);
    }

    public function getNotifications()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $notifications = $this->notificationRepository->getNotifications(ShopUser::class, $shop_user->id);
        return response()->json(['data' => $notifications, 'message' => 'Successfully Get Notifications', 'status' => 'success'], 200);
    }

    public function removeNotification(Request $request)
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $notification_id = $request->notification_id;
        $notifications = $this->notificationService->removeNotificationByUser($notification_id, $shop_user->id, ShopUser::class);
        return response()->json(['data' => $notifications, 'message' => 'Successfully Remove Notification', 'status' => 'success'], 200);
    }

    public function makeNoticationRead(Request $request)
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $notification_id = $request->notification_id;
        $notifications = $this->notificationService->makeNotificationReadByUser($notification_id, $shop_user->id, ShopUser::class);
        return response()->json(['data' => $notifications, 'message' => 'Successfully make notification read', 'status' => 'success'], 200);
    }

    public function getNotificationCount()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $notifications = $this->notificationRepository->getUnreadNotificationCount(ShopUser::class, $shop_user->id);
        return response()->json(['data' => $notifications, 'message' => 'Successfully get notification count', 'status' => 'success'], 200);
    }

    public function changePassword(Request $request)
    {
        $old_password = $request->oldPassword;
        $new_password = $request->newPassword;
        $shopUser = auth()->guard('shop-user-api')->user();
        $password = $this->shopUserService->changePassword($shopUser, $old_password, $new_password);
        if ($password) {
            return response()->json(['data' => $shopUser, 'message' => 'Successfully changed password', 'status' => 'success'], 200);
        } else {
            return response()->json(['data' => $shopUser, 'message' => 'Password wronged', 'status' => 'failed'], 200);
        }
    }

    public function getShopUsers()
    {
        $shop_users = $this->shopUserRepository->getAllShopUsers();
        return response()->json([
            'data' => $shop_users,
            'message' => 'Successfully get shop users',
            'status' => 'success'], 200);
    }

    public function store(ShopUserCreateRequest $request)
    {
        $data = $request->all();
        $shop_user = $this->shopUserService->saveShopUserData($data);
        return response()->json([
            'data' => $shop_user,
            'message' => 'Successfully created new shop user',
            'status' => 'success'], 201);
    }

    public function updateShopUser(Request $request, $id)
    {
        $data = $request->all();
        $shop_user = $this->shopUserService->updateShopUserData($data, $id);
        return response()->json([
            'data' => $shop_user,
            'message' => 'Successfully updated shop user',
            'status' => 'success'], 200);
    }

    public function checkPassword(Request $request, $id)
    {
        $password = $request->password;
        $is_validate_password = $this->shopUserService->checkPassword($password, $id);
        if($is_validate_password) {
            return response()->json([
                'message' => 'Password validation passed',
                'status' => 'success'], 200);
        } else {
            return response()->json([
                'message' => 'Password validation failed',
                'status' => 'fail'], 401);
        }
    }
}
