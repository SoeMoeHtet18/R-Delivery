<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Repositories\BranchRepository;
use App\Services\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $branchRepository;
    protected $branchService;

    public function __construct(BranchRepository $branchRepository, BranchService $branchService)
    {
        $this->branchRepository = $branchRepository;
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.branches.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.branches.create');
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
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
