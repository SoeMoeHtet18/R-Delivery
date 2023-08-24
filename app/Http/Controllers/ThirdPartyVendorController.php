<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThirdPartyVendorRequest;
use App\Http\Requests\TownshipAssignRequest;
use App\Models\ThirdPartyVendor;
use App\Models\Township;
use App\Repositories\ThirdPartyVendorRepository;
use App\Services\ThirdPartyVendorService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ThirdPartyVendorController extends Controller
{
    protected $thirdPartyVendorRepository;
    protected $thirdPartyVendorService;

    public function __construct(ThirdPartyVendorRepository $thirdPartyVendorRepository, ThirdPartyVendorService $thirdPartyVendorService)
    {
        $this->thirdPartyVendorRepository = $thirdPartyVendorRepository;
        $this->thirdPartyVendorService = $thirdPartyVendorService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.third-party-vendor.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.third-party-vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ThirdPartyVendorRequest $request)
    {
        $data = $request->all();
        $this->thirdPartyVendorService->saveThirdPartyVendorData($data);
        return redirect(route('third-party-vendor.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $thirdPartyVendor = $this->thirdPartyVendorRepository->show($id);
        $townships = $thirdPartyVendor->townships;
        return view('admin.third-party-vendor.detail', compact('thirdPartyVendor', 'townships'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $thirdPartyVendor = $this->thirdPartyVendorRepository->show($id);
        return view('admin.third-party-vendor.edit',compact('thirdPartyVendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ThirdPartyVendorRequest $request, $id)
    {
        $thirdPartyVendor = $this->thirdPartyVendorRepository->show($id);
        $data = $request->all();
        $this->thirdPartyVendorService->updateThirdPartyVendorByID($data, $thirdPartyVendor);

        return redirect()->route('third-party-vendor.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->thirdPartyVendorService->deleteThirdPartyVendorByID($id);
        return redirect()->route('third-party-vendor.index');
    }

    public function assignTownship($id)
    {
        $thirdPartyVendor = $this->thirdPartyVendorRepository->show($id);
        $townships = Township::where(['associable_id' => null, 'associable_type' => null])->get();
        $assignedTownships = $thirdPartyVendor->townships;
        $townships = $townships->merge($assignedTownships)->unique();
        $assignedTownshipID = [];
        foreach($assignedTownships as $assignedTownship) {
            $assignedTownshipID[] = $assignedTownship->id;
        }
        // $riders = $township->riders;
        return view('admin.third-party-vendor.assign_township', compact('thirdPartyVendor', 'townships', 'assignedTownshipID'));
    }
    
    public function saveAssignTownship(TownshipAssignRequest $request,$id)
    {
        $thirdPartyVendor = $this->thirdPartyVendorRepository->show($id);
        $data = $request->all();
        $assignedTownships = $thirdPartyVendor->townships;
        if(count($assignedTownships) > 0){
            foreach($assignedTownships as $assignedTownship) {
                $assignedTownship = $assignedTownship->update(['associable_id'=>null,'associable_type'=>null]);
            }
        }
        $townships = Township::whereIn('id',$data['township_id'])->get();
        foreach($townships as $township) {
            $township->associable()->associate($thirdPartyVendor);
            $township->save();
        }
        // $riders = $township->riders;
        return redirect()->route('third-party-vendor.show', $id);
    }

    public function getAjaxThirdPartyVendorData(Request $request)
    {
        $data = $this->thirdPartyVendorRepository->getAllThirdPartyVendorQuery();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($thirdPartyVendor) {
                $actionBtn = '
                        <a href="' . route("third-party-vendor.show", $thirdPartyVendor->id) . '" class="edit btn btn-info btn-sm">View</a> 
                        <a href="' . route("third-party-vendor.edit", $thirdPartyVendor->id) . '" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="' . route("third-party-vendor.destroy", $thirdPartyVendor->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this third party vendor?`);">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-third_party_vendors.id')
            ->make(true);
    }
}
