<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $shops = Shop::select('*');
            return DataTables::of($shops)
                ->addIndexColumn()
                ->addColumn('action', function($shops) {
                    $actionBtns =  '
                        <a href="'. route("shops.show", $shops->id) .'" class="btn btn-info btn-sm">View</a>
                        <a href="'. route("shops.edit", $shops->id) .'" class="btn btn-light btn-sm">Edit</a>
                        <form action="'. route("shops.destroy", $shops->id) .'" method="post" class="d-inline">
                            <input type="hidden" name="_token" value="'. csrf_token() .'"/>
                            <input type="hidden" name="_method" value="DELETE"/>
                            <input type="submit" value="Delete" class="btn btn-danger btn-sm"/>
                        </form>';
                    return $actionBtns;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.shop.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shop.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string|unique:shops'
        ];

        $customErr = [
            'name.required' => 'Name field is required',
            'address.required' => 'Address field is required',
            'phone_number.required' => 'Phone Number is required',
            'phone_number.unique' => 'Phone Number already exists'
        ];

        $validator = Validator::make($request->all(), $rules, $customErr);
        if  ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $shop = new Shop();
            $shop->name = $request->name;
            $shop->address =  $request->address;
            $shop->phone_number = $request->phone_number;
            $shop->save();
        }
        return redirect(route('shops.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shop = Shop::where('id',$id)->first();
        if(!$shop) {
            abort(404);
        }
        return view('admin.shop.detail', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shop = Shop::where('id',$id)->first();
        if(!$shop) {
            abort(404);
        }
        return view('admin.shop.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shop = Shop::where('id', $id)->first();

        $rules = [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string|unique:shops'
        ];

        $customErr = [
            'name.required' => 'Name field is required',
            'address.required' => 'Address field is required',
            'phone_number.required' => 'Phone Number is required',
            'phone_number.unique' => 'Phone Number already exists'
        ];

        $validator = Validator::make($request->all(), $rules, $customErr);
        if  ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $shop->name = $request->name;
            $shop->address =  $request->address;
            $shop->phone_number = $request->phone_number;
            $shop->save();
        }
        return redirect(route('shops.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Shop::destroy($id);
        return redirect(route('shops.index'));
    }
}
