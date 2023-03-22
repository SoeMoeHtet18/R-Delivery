<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repositories\AdminRepository;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    protected $adminService;
    protected $adminRepository;

    public function __construct(AdminService $adminService, AdminRepository $adminRepository)
    {
        $this->adminService = $adminService;
        $this->adminRepository = $adminRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->adminRepository->getAllUsersQuery();
            return DataTables::of($data)
                
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="' . route("users.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                    <a href="' . route("users.edit", $row->id) . '" class="edit btn btn-light btn-sm" >Edit</a>
                    <form action="'.route("users.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this user?`);">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                    </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->orderColumn('id', '-id $1')
                ->make(true);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        $data = $request->all();
        $this->adminService->saveAdminData($data);
        
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->adminRepository->getUserById($id);
        
        return view('admin.user.detail',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = $this->adminRepository->getUserById($id);
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = $this->adminRepository->getUserById($id);
        $data = $request->all();
        $this->adminService->updateAdminData($data,$user);    

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->adminService->deleteUserByID($id);
        return redirect()->route('users.index');
    }
}
