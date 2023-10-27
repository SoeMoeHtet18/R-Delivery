<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use App\Services\AdminService;
use Carbon\Carbon;

class AdminApiController extends Controller
{
    protected $adminRepository;
    protected $adminService;

    public function __construct(AdminRepository $adminRepository, AdminService $adminService)
    {
        $this->adminRepository = $adminRepository;
        $this->adminService    = $adminService;
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

    public function storeUserData(Request $request) {
        $data = $request->data;
        $user = $this->adminService->saveAdminData($data);
        return response()->json([
            'data'   => $user,
            'message'=> 'Successfully save user',
            'status' => 'success'
        ], 200);
    }
    
    public function updateUserData(Request $request) {
        $data = $request->data;
        $user = $this->adminService->updateUserData($data);
        return response()->json([
            'data'   => $user,
            'message'=> 'Successfully save user',
            'status' => 'success'
        ], 200);
    }

    public function getNotifications() {
        $todayDate = Carbon::today()->format('Y-m-d');
        $notifications = auth()->user()->notifications;
        $todayNotifications = [];
        foreach($notifications as $notification){
            $createdAt = Carbon::parse($notification->created_at);
            if($createdAt->isSameDay($todayDate)){
                $notification['latest_time'] = Carbon::parse($notification->created_at)->toDateTimeString();
                $todayNotifications[] = $notification;
            }
        }
        return response()->json([
            'data'   => $todayNotifications,
            'message'=> 'Successfully get notifications',
            'status' => 'success'
        ], 200);
    }
}
