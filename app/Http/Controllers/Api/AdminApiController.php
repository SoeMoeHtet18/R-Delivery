<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\AdminRepository;
use App\Services\AdminService;
use App\Services\NotificationService;
use Carbon\Carbon;

class AdminApiController extends Controller
{
    protected $adminRepository;
    protected $adminService;
    protected $notificationService;

    public function __construct(AdminRepository $adminRepository,
        AdminService $adminService,
        NotificationService $notificationService)
    {
        $this->adminRepository = $adminRepository;
        $this->adminService    = $adminService;
        $this->notificationService = $notificationService;
    }

    public function getUserTableData(Request $request)
    {
        $search = $request->search;
        $data = $this->adminRepository->getAllAdminData($search);
        return response()->json([
            'data' => $data,
            'message' => 'Successfully get user table data',
            'status' => 'success'
        ], 200);
    }

    public function storeUserData(Request $request)
    {
        $data = $request->data;
        $user = $this->adminService->saveAdminData($data);
        return response()->json([
            'data'   => $user,
            'message'=> 'Successfully save user',
            'status' => 'success'
        ], 200);
    }
    
    public function updateUserData(Request $request)
    {
        $data = $request->data;
        $user = $this->adminService->updateUserData($data);
        return response()->json([
            'data'   => $user,
            'message'=> 'Successfully save user',
            'status' => 'success'
        ], 200);
    }

    public function getNotifications()
    {
        $todayDate = Carbon::today()->format('Y-m-d');
        $notifications = auth()->user()->notifications;
        $todayNotifications = [];
        foreach($notifications as $notification){
            $createdAt = Carbon::parse($notification->created_at);
            if($createdAt->isSameDay($todayDate)){
                $todayNotifications[] = $notification;
                $todayNotifications['latest_time'] = Carbon::parse($notification->created_at)->toDateTimeString();
            }
        }
        return response()->json([
            'data'   => $todayNotifications,
            'message'=> 'Successfully get notifications',
            'status' => 'success'
        ], 200);
    }

    public function makeNotificationRead(string $id)
    {
        $user_id = auth()->user()->id;
        
        $notification = $this->notificationService->makeNotificationReadByUser($id, $user_id, User::class);

        return response()->json([
            'data'   => $notification,
            'message'=> 'Successfully make notification read',
            'status' => 'success'
        ], 200);
    }

    public function getNewNotifications(Request $request)
    {
        $lastNotificationTime = $request->input('latest_time');
        $formattedLastNotificationTime = Carbon::parse($lastNotificationTime)->toDateTimeString();
        $notifications = auth()->user()->notifications;
        $newNotifications = [];
        if($lastNotificationTime != null) {
            foreach($notifications as $notification) {
                if($notification->created_at > $formattedLastNotificationTime){
                    $newNotifications['latest_time'] = Carbon::parse($notification->created_at)->toDateTimeString();
                    $newNotifications[] = $notification;
                }
            }
        }
        
        return response()->json([
            'data'   => $newNotifications,
            'message'=> 'Successfully get new notifications',
            'status' => 'success'
        ], 200);
    }
}
