<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ShopUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ShopUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ShopUser::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $actionBtn = '
                            <a href="'. route("shopusers.show", $data->id) .'" class="edit btn btn-info btn-sm">View</a> 
                            <a href="'. route("shopusers.edit", $data->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                            <form action="'.route("shopusers.destroy", $data->id) .'" method="post" class="d-inline">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.shopuser.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = Shop::all();

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
            'device_id'         => 'required',
            'password'          => 'required|min:8',
        ];

        $customErr = [
            'name.required'              => 'Name field is required',
            'phone_number.required'      => 'Phone Number is required',
            'phone_number.unique'        => 'Phone Number already exists',
            'email'                      => 'Email already exists',
            'device_id.required'         => 'Device ID is required',
            'password.required'          => 'Password is required',
            'password.min'               => 'Password should be a minimum of 8 characters.',
        ];
        
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $shopuser = new ShopUser();
            $shopuser->name = $request->name;
            $shopuser->phone_number = $request->phone_number ? $request->phone_number : null;
            $shopuser->email = $request->email;
            $shopuser->password = $request->password? bcrypt($request->password): null;
            $shopuser->device_id = $request->device_id ? $request->device_id : null;
            $shopuser->shop_id = $request->shop_id;
            $shopuser->save();
        }

        return redirect()->route('shopusers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shopuser = ShopUser::where('id',$id)->first();
        if(!$shopuser) {
            abort(404);
        }
        $shop = Shop::where('id', $shopuser->shop_id)->first();
        $shopuser->shop_name = $shop->name;
        
        return view('admin.shopuser.detail',compact('shopuser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shopuser = ShopUser::where('id',$id)->first();
        if(!$shopuser) {
            abort(404);
        }
        $shops = Shop::all();

        return view('admin.shopuser.edit',compact('shopuser','shops'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        {
            $rules = [ 
                'name'                  => 'required|string',
                'phone_number'          => 'required|string|unique:shop_users,phone_number,'.$id,
                'email'                 => 'unique:shop_users,email,'.$id,
                'device_id'             => 'required',
            ];
                
            $customErr = [
                'name.required'                 => 'Name field is required.',
                'phone_number.required'         => 'Phone Number is required.',
                'phone_number.unique'           => 'Phone Number already exists.',
                'email.unique'                  => 'Email already exists.',
                'device_id.required'            => 'Device ID is required.',
            ];
            $validator = Validator::make($request->all(), $rules,$customErr);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $shopuser = ShopUser::where('id',$id)->first();
                $shopuser->name = $request->name;
                $shopuser->phone_number = $request->phone_number;
                $shopuser->email = $request->email;
                if ($request->password) {
                    $shopuser->password = bcrypt($request->password);
                }
                $shopuser->device_id = $request->device_id;
                $shopuser->shop_id = $request->shop_id;
                $shopuser->save();
            }
    
            return redirect()->route('shopusers.show', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ShopUser::destroy($id);
        return redirect()->route('shopusers.index');
    }
}
