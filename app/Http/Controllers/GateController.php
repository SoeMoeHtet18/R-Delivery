<?php

namespace App\Http\Controllers;

use App\Http\Requests\GateCreateRequest;
use App\Models\Gate;
use App\Repositories\CityRepository;
use App\Repositories\GateRepository;
use App\Repositories\TownshipRepository;
use App\Services\GateService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GateController extends Controller
{
    protected $cityRepository;
    protected $townshipRepository;
    protected $gateService;
    protected $gateRepository;

    public function __construct(CityRepository $cityRepository, TownshipRepository $townshipRepository, GateService $gateService, GateRepository $gateRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->townshipRepository = $townshipRepository;
        $this->gateService = $gateService;
        $this->gateRepository = $gateRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = $this->cityRepository->getAllCities();
        return view('admin.gate.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        $townships = $this->townshipRepository->getAllTownships();
        $townships = $townships->sortByDesc('id');
        return view('admin.gate.create', compact('cities','townships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GateCreateRequest $request)
    {
        $data = $request->all();
        $this->gateService->saveGateData($data);
        return redirect(route('gates.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $gate = $this->gateRepository->show($id);
        $gateAssignedTownships = [];
        if(count($gate->townships) > 0 ){
            foreach($gate->townships as $township) {
                $gateAssignedTownships[] = $township->name ;
            }
        } 
        $townships = implode(', ',$gateAssignedTownships);
        return view('admin.gate.detail', compact('gate','townships'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $gate = $this->gateRepository->show($id);
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        $townships = $this->townshipRepository->getAllTownships();
        $townships = $townships->sortByDesc('id');
        $assignedTownship = array();   
        if(count($gate->townships) > 0) {            
            foreach ($gate->townships as $key => $value) {
                $assignedTownship[] = $value->id;
            }
        }
        return view('admin.gate.edit', compact('gate', 'cities', 'assignedTownship','townships'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $gate = $this->gateRepository->show($id);
        $data = $request->all();
        $this->gateService->updateGateByID($data, $gate);

        return redirect()->route('gates.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->gateService->deleteGateByID($id);
        return redirect()->route('gates.index');
    }

    public function getAjaxGateData(Request $request)
    {
        $city_name = $request->city_name;
        $data = $this->gateRepository->getAllGateQuery();

        if ($city_name != null) {
            $data = $data->where('gates.city_id', $city_name);
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($gate) {
                $actionBtn = '
                        <a href="' . route("gates.show", $gate->id) . '" class="edit btn btn-info btn-sm">View</a> 
                        <a href="' . route("gates.edit", $gate->id) . '" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="' . route("gates.destroy", $gate->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this gate?`);">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-gates.id')
            ->make(true);
    }
}
