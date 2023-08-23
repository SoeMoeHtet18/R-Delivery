<?php

namespace App\Http\Controllers;

use App\Http\Requests\TownshipRequest;
use App\Models\Branch;
use App\Models\City;
use App\Models\Gate;
use App\Models\ThirdPartyVendor;
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
        $cities = $this->cityRepository->getAllCities();
        return view('admin.township.index',compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        return view('admin.township.create',compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TownshipRequest $request)
    {   
        $data = $request->all();
        $this->townshipService->saveTownshipData($data);

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
        $cities = $cities->sortByDesc('id');
        return view('admin.township.edit',compact('township', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TownshipRequest $request, string $id)
    {   
        $data = $request->all();
        $township = $this->townshipRepository->getTownshipById($id);
        $this->townshipService->updateTownshipByID($data,$township);

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

    public function getTownshipsTableByCityID($id)
    {
        $townships = $this->townshipRepository->getAllTownshipsByCityIDQuery($id);
        return DataTables::of($townships)
            ->addColumn('name', function($townships) {
                return '<a href="'. route("townships.show",$townships->id) . '">' . $townships->name . '</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['name'])
            ->orderColumn('id', '-id $1')
            ->make(true);
    }

    public function getAjaxTownshipData(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $data = $this->townshipRepository->getAllTownshipsQuery();
        if($search) {
            $data = $data->where('townships.name','like','%' . $search . '%');
        }
        if($city != null) {
            $data = $data->where('townships.city_id',$city);
        }
        return DataTables::of($data)

            ->addIndexColumn()
            ->addColumn('action', function($data){
                $actionBtn = '
                        <a href="'. route("townships.show", $data->id) .'" class="btn btn-info btn-sm">View</a> 
                        <a href="'. route("townships.edit", $data->id) .'" class="btn btn-light btn-sm">Edit</a> 
                        <form action="'.route("townships.destroy", $data->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this township?`);">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                    </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-townships.id $1')
            ->make(true);
    }

    public function getTownshipListByAssociable(Request $request)
    {
        $city_id = $request->city_id;
        $associable_type = $request->associable_type;
        $associable_id = $request->associable_id;
        $townships = $this->townshipRepository->getTownshipListByAssociable($city_id, $associable_id, $associable_type);
        // $townships = $townships->sortByDesc('id');
        return response()->json(['data' => $townships, 'message' => 'Successfully Get Townships List', 'status' => 'success'], 200);
    }

    public function getTownshipsWithAssociable(Request $request)
    {
        $type = $request->type;
        $id   = $request->id;
        if($type == 'branch'){
            $associableType = Branch::class;
        } elseif($type == 'gate'){
            $associableType = Gate::class;
        } else {
            $associableType = ThirdPartyVendor::class;
        }
        $townships = $this->townshipRepository->getTownshipsWithAssociable($id, $associableType);
        return DataTables::of($townships)
            ->addColumn('name', function($townships) {
                return '<a href="'. route("townships.show",$townships->id) . '">' . $townships->name . '</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['name'])
            ->orderColumn('id', '-id $1')
            ->make(true);
    }
}
