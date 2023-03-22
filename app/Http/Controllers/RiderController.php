<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use App\Repositories\RiderRepository;
use App\Services\RiderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RiderController extends Controller
{
    protected $riderRepository;
    protected $riderService;

    public function __construct(RiderRepository $riderRepository, RiderService $riderService)
    {
        $this->riderRepository = $riderRepository;
        $this->riderService = $riderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $riders = $this->riderRepository->getAllRidersQuery();
            return DataTables::of($riders)
                ->addIndexColumn()
                ->addColumn('action', function($riders){
                    $actionBtn = '
                        <a href="'. route("riders.show", $riders->id) .'" class="edit btn btn-info btn-sm">View</a> 
                        <a href="'. route("riders.edit", $riders->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="'.route("riders.destroy", $riders->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this rider?`);">
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
        return view('admin.rider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [ 
            'name'                  => 'required|string',
            'phone_number'          => 'required|string|unique:riders',
            'email'                 => 'unique:riders',
            'password'              => 'required|min:8'
        ];
            
        $customErr = [
            'name.required'                 => 'Name field is required.',
            'phone_number.required'         => 'Phone Number is required.',
            'phone_number.unique'           => 'Phone Number already exists.',
            'email'                         => 'Email already exists',
            'password.required'             => 'Password is required', 
            'password.min'                  => 'Password should be a minimum of 8 characters.',
        ];

        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();
            
            $this->riderService->saveRiderData($data);
        }

        return redirect(route('riders.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);

        return view('admin.rider.detail', compact('rider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);

        return view('admin.rider.edit', compact('rider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);

        $rules = [ 
            'name'                  => 'required|string',
            'phone_number'          => 'required|string|unique:riders,phone_number,' . $id,
            'email'                 => 'unique:riders,email,' . $id,
        ];
            
        $customErr = [
            'name.required'                 => 'Name field is required.',
            'phone_number.required'         => 'Phone Number is required.',
            'phone_number.unique'           => 'Phone Number already exists.',
            'email.unique'                  => 'Email already exists',
        ];

        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();
            $this->riderService->updateRiderByID($data,$rider);
        }

        return redirect(route('riders.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->riderService->deleteRiderByID($id);
        return redirect(route('riders.index'));
    }
}
