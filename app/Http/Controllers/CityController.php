<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Repositories\CityRepository;
use App\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{

    protected $cityRepository;
    protected $cityService;
    public function __construct(CityRepository $cityRepository, CityService $cityService)
    {
        $this->cityRepository = $cityRepository;
        $this->cityService    = $cityService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = City::select('*')->orderBy('id','DESC');
            return DataTables::of($data)
                
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="' . route("cities.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                    <a href="' . route("cities.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                    <form action="'.route("cities.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this city?`);">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                    </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.city.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.city.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            $data = $request->all();
            $this->cityService->saveCityData($data);
        }

        return redirect()->route('cities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $city = $this->cityRepository->getByCityID($id);
        return view('admin.city.detail',compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $city = $this->cityRepository->getByCityID($id);
        return view('admin.city.edit',compact('city'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
            $data = $request->all();
            $city = $this->cityRepository->getByCityID($id);
            $this->cityService->updateCityByID($data,$city);
        }

        return redirect(route('cities.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->cityService->deleteCityByID($id);
        return redirect()->route('cities.index');
    }
}
