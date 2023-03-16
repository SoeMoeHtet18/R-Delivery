<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RidersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $riders = Rider::select('*');
            return DataTables::of($riders)
                ->addIndexColumn()
                ->addColumn('action', function($riders){
                    $actionBtn = '
                        <a href="'. route("riders.show", $riders->id) .'" class="edit btn btn-info btn-sm">View</a> 
                        <a href="'. route("riders.edit", $riders->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="'.route("riders.destroy", $riders->id) .'" method="post" class="d-inline">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
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
            'device_id'             => 'required',
            'password'              => 'required|min:8'
        ];
            
        $customErr = [
            'name.required'                 => 'Name field is required.',
            'phone_number.required'         => 'Phone Number is required.',
            'phone_number.unique'           => 'Phone Number already exists.',
            'email'                         => 'Email already exists',
            'device_id.required'            => 'Device ID is required.',
            'password.required'             => 'Password is required', 
            'password.min'                  => 'Password should be a minimum of 8 characters.',
        ];
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $rider = new Rider();
            $rider->name = $request->name;
            $rider->phone_number = $request->phone_number;
            $rider->email = $request->email? $request->email : null;
            $rider->password = bcrypt($request->password);
            $rider->device_id = $request->device_id;
            $rider->save();
        }

        return redirect(route('riders.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rider = Rider::where('id', $id)->first();
        if(!$rider) {
            abort(404);
        }

        return view('admin.rider.detail', compact('rider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rider = Rider::where('id', $id)->first();
        if(!$rider) {
            abort(404);
        }

        return view('admin.rider.edit', compact('rider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rider = Rider::where('id',$id)->first();
        if(!$rider) {
            abort(404);
        }
        
        $rules = [ 
            'name'                  => 'required|string',
            'phone_number'          => 'required|string|unique:riders,phone_number,' . $id,
            'email'                 => 'unique:riders,email,' . $id,
            'device_id'             => 'required',
        ];
            
        $customErr = [
            'name.required'                 => 'Name field is required.',
            'phone_number.required'         => 'Phone Number is required.',
            'phone_number.unique'           => 'Phone Number already exists.',
            'email.unique'                  => 'Email already exists',
            'device_id.required'            => 'Device ID is required.',
        ];
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $rider->name = $request->name;
            $rider->phone_number = $request->phone_number;
            $rider->email = $request->email? $request->email : null;
            if($request->password) {
                $rider->password =  bcrypt($request->password);
            }
            $rider->device_id = $request->device_id;
            $rider->save();
        }

        return redirect(route('riders.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Rider::destroy($id);
        return redirect(route('riders.index'));
    }
}
