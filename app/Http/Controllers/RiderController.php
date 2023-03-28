<?php

namespace App\Http\Controllers;

use App\Http\Requests\RiderCreateRequest;
use App\Http\Requests\RiderUpdateRequest;
use App\Models\Rider;
use App\Repositories\RiderRepository;
use App\Repositories\TownshipRepository;
use App\Services\RiderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RiderController extends Controller
{
    protected $riderRepository;
    protected $riderService;
    protected $townshipRepository;
    
    public function __construct(RiderRepository $riderRepository, RiderService $riderService, TownshipRepository $townshipRepository)
    {
        $this->riderRepository = $riderRepository;
        $this->riderService = $riderService;
        $this->townshipRepository = $townshipRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $riders = $this->riderRepository->getAllRidersQuery();
            return DataTables::of($riders)
                ->addIndexColumn()
                ->addColumn('action', function($riders){
                    $actionBtn = '
                        <a href="'. url("/riders/" . $riders->id . "/assign-township") .'" class="btn btn-secondary btn-sm">Assign Township</a>
                        <a href="'. route("riders.show", $riders->id) .'" class="edit btn btn-info btn-sm">View</a> 
                        <a href="'. route("riders.edit", $riders->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="'.route("riders.destroy", $riders->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this rider?`);">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->orderColumn('id', '-id $1')
                ->make(true);
        }
        return view('admin.rider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $townships = $this->townshipRepository->getAllTownships();
        $townships = $townships->sortByDesc('id');
        return view('admin.rider.create', compact('townships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RiderCreateRequest $request)
    {
        $data = $request->all();
        $this->riderService->saveRiderData($data);
        return redirect(route('riders.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $townships = $this->townshipRepository->getAllTownships();

        return view('admin.rider.detail', compact('rider', 'townships'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $townships = $this->townshipRepository->getAllTownships();
        $townships = $townships->sortByDesc('id');
        return view('admin.rider.edit', compact('rider', 'townships'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RiderUpdateRequest $request, string $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $data = $request->all();
        $this->riderService->updateRiderByID($data,$rider);

        return redirect(route('riders.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->riderService->deleteRiderByID($id);
        return redirect(route('riders.index'));
    }

    public function assignTownship($id)
    {
        $townships = $this->townshipRepository->getAllTownships();
        $townships = $townships->sortByDesc('id');
        $rider = $this->riderRepository->getRiderByID($id);
        return view('admin.rider.assign_township', compact('rider', 'townships'));
    }

    public function assignTownshipToRider(Request $request, $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $data = $request->all();
        $this->riderService->assignTownship($rider, $data);
        return redirect(route('riders.show', $id));
    }
}
