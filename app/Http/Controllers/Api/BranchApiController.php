<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\BranchRepository;
use App\Services\BranchService;

class BranchApiController extends Controller
{
    protected $branchRepository;
    protected $branchService;

    public function __construct(BranchRepository $branchRepository, BranchService $branchService)
    {
        $this->branchRepository = $branchRepository;
        $this->branchService    = $branchService;
    }

    public function getBranchTableData(Request $request)
    {
        $search = $request->search;
        $data = $this->branchRepository->getAllBranchData($request, $search);
        return response()->json([
            'data' => $data,
            'message' => 'Successfully get branch table data',
            'status' => 'success'
        ], 200);
    }

    public function storeBranchData(Request $request) {
        $data = $request->data;
        $branch = $this->branchService->saveBranchData($data);
        return response()->json([
            'data'   => $branch,
            'message'=> 'Successfully save branch',
            'status' => 'success'
        ], 200);
    }
    
    public function getBranchDetail($id) {
        $data = $this->branchRepository->getBranchById($id);
        return response()->json([
            'data' => $data,
            'message' => 'Successfully get branch data',
            'status' => 'success'
        ], 200);
    }
}
