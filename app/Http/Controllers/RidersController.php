<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                        <form action="'.route("riders.destroy", $riders->id) .'" method="post">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-danger"/>
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
        $validator = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'password' => 'required',
            'device_id' => 'required'
        ]);

        $rider = new Rider();
        $rider->name = $request->name;
        $rider->phone_number = $request->phone_number;
        $rider->email = $request->email? $request->email : null;
        $rider->password = bcrypt($request->password);
        $rider->device_id = $request->device_id;
        $rider->save();

        return redirect(route('riders.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        Rider::destroy($id);
        return redirect(route('riders.index'));
    }
}
