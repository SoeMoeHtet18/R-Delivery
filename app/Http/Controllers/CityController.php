<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Repositories\CityRepository;
use App\Repositories\TownshipRepository;
use App\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{

    protected $cityRepository;
    protected $cityService;
    protected $townshipRepository;

    public function __construct(CityRepository $cityRepository, CityService $cityService, TownshipRepository $townshipRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->cityService    = $cityService;
        $this->townshipRepository = $townshipRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
    public function store(CityRequest $request)
    {
        $data = $request->all();
        $this->cityService->saveCityData($data);

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
    public function update(CityRequest $request, string $id)
    {
        $data = $request->all();
        $city = $this->cityRepository->getByCityID($id);
        $this->cityService->updateCityByID($data,$city);
        
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

    public function getAjaxCityData(Request $request)
    {
        $search = $request->search;
        $data = $this->cityRepository->getAllCitiesQuery();
        if($search) {
            $data = $data->where('cities.name','like', '%' . $search . '%');
        }
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
            ->orderColumn('id', '-id $1')
            ->make(true);
        
    }
}
