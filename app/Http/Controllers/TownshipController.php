<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Township;
use App\Repositories\CityRepository;
use App\Repositories\TownshipRepository;
use App\Services\TownshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TownshipController extends Controller
{   
    protected $townshipRepository;
    protected $townshipService;
    protected $cityRepository;

    public function __construct(TownshipRepository $townshipRepository,TownshipService  $townshipService,CityRepository $cityRepository)
    {   
        $this->townshipRepository = $townshipRepository;
        $this->townshipService = $townshipService;
        $this->cityRepository = $cityRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        if ($request->ajax()) {
            $data = Township::leftJoin('cities','cities.id','townships.city_id')->select('townships.*','cities.name as city_name');
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
        $cities = $this->cityRepository->getAllCities();
        return view('admin.township.create',compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'           => 'required|string',
            'city'           => 'required',
        ];

        $customErr = [
            'name.required'     => 'Name field is required',
            'city.required'     => 'City field is required',
        ];
        
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();
            $this->townshipService->saveTownshipData($data);
        }

        return redirect()->route('townships.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $township = $this->townshipRepository->getTownshipById($id);
        return view('admin.township.detail',compact('township'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $township = $this->townshipRepository->getTownshipById($id);
        $cities = $this->cityRepository->getAllCities();
        return view('admin.township.edit',compact('township', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $rules = [ 
            'name'                  => 'required|string',
            'city'                  => 'required',
        ];
            
        $customErr = [
            'name.required'         => 'Name field is required.',
            'city.required'         => 'City field is required.',
        ];
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();
            $township = $this->townshipRepository->getTownshipById($id);
            $this->townshipService->updateTownshipByID($data,$township);
        }

        return redirect(route('townships.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->townshipService->deleteTownshipByID($id);
        return redirect()->route('townships.index');
    }
}
