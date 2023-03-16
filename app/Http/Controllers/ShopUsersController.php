<?php

namespace App\Http\Controllers;

use App\Models\ShopUser;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShopUsersController extends Controller
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
                    $actionBtn = '<a href="' . route("shopusers.show", $data->id) . '" class="info btn btn-info btn-sm">View</a>
                                <a href="' . route("shopusers.edit", $data->id) . '" class="edit btn btn-light btn-sm">Edit</a> 
                                <a href="' . route("shopusers.destroy", $data->id) . '" class="delete btn btn-danger btn-sm">Delete</a>';

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shopuser = ShopUser::where('id',$id)->first();
        return view('admin.shopuser.detail',compact('shopuser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
