<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Repositories\BranchRepository;
use App\Repositories\CityRepository;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
    protected $branchRepository;
    protected $branchService;
    protected $cityRepository;

    public function __construct(BranchRepository $branchRepository, BranchService $branchService,CityRepository $cityRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->branchService = $branchService;
        $this->cityRepository = $cityRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = $this->cityRepository->getAllCities();
        return view('admin.branches.index',compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        return view('admin.branches.create',compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->branchService->saveBranchData($data);
        return redirect(route('branches.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $branch = $this->branchRepository->show($id);
        return view('admin.branches.detail', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cities = $this->cityRepository->getAllCities();
        $cities = $cities->sortByDesc('id');
        $branch = $this->branchRepository->show($id);
        return view('admin.branches.edit',compact('branch','cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $branch = $this->branchRepository->show($id);
        $data = $request->all();
        $this->branchService->updateBranchByID($data, $branch);

        return redirect()->route('branches.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->branchService->deleteBranchByID($id);
        return redirect()->route('branches.index');
    }

    public function getAjaxBranchData(Request $request)
    {
        $city = $request->city;
        $data = $this->branchRepository->getAllBranchQuery();

        if ($city != null) {
            $data = $data->where('branches.city_id', $city);
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($branch) {
                $actionBtn = '
                        <a href="' . route("branches.show", $branch->id) . '" class="edit btn btn-info btn-sm">View</a> 
                        <a href="' . route("branches.edit", $branch->id) . '" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="' . route("branches.destroy", $branch->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this Branch?`);">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-branches.id')
            ->make(true);
    }
}
