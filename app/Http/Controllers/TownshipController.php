<?php

namespace App\Http\Controllers;

use App\Models\Township;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TownshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        if ($request->ajax()) {
            $data = Township::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $actionBtn = '
                            <a href="'. route("townships.show", $data->id) .'" class="btn btn-info btn-sm">View</a> 
                            <a href="'. route("townships.edit", $data->id) .'" class="btn btn-light btn-sm">Edit</a> 
                            <form action="'.route("townships.destroy", $data->id) .'" method="post" class="d-inline">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.township.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.township.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'              => 'required|string',
        ];

        $customErr = [
            'name.required'     => 'Name field is required',
        ];
        
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $township = new Township();
            $township->name = $request->name;
            $township->save();
        }

        return redirect()->route('townships.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $township = Township::where('id', $id)->first();
        if(!$township) {
            abort(404);
        }
        return view('admin.township.detail',compact('township'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $township = Township::where('id',$id)->first();
        if(!$township) {
            abort(404);
        }
        return view('admin.township.edit',compact('township'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        {
            $rules = [ 
                'name'                  => 'required|string',
            ];
                
            $customErr = [
                'name.required'                 => 'Name field is required.',
            ];
            $validator = Validator::make($request->all(), $rules,$customErr);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $township = Township::where('id',$id)->first();
                $township->name = $request->name;
                $township->save();
            }
    
            return redirect()->route('townships.show', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Township::destroy($id);
        return redirect()->route('townships.index');
    }
}
