<?php

namespace App\Http\Controllers;

use App\Http\Requests\RiderPaymentRequest;
use App\Repositories\RiderPaymentRepository;
use App\Repositories\RiderRepository;
use App\Services\RiderPaymentService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RiderPaymentController extends Controller
{
    protected $riderRepository;
    protected $riderPaymentRepository;
    protected $riderPaymentService;

    public function __construct(RiderRepository $riderRepository, RiderPaymentRepository $riderPaymentRepository, RiderPaymentService $riderPaymentService)
    {
        $this->riderRepository = $riderRepository;
        $this->riderPaymentRepository = $riderPaymentRepository;
        $this->riderPaymentService = $riderPaymentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $riders = $this->riderRepository->getAllRiders();
        return view('admin.rider-payment.index',compact('riders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');
        return view('admin.rider-payment.create', compact('riders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RiderPaymentRequest $request)
    {
        $data = $request->all();
        // dd($data);
        $this->riderPaymentService->saveRiderPaymentData($data);
        return redirect(route('rider-payments.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rider_payment = $this->riderPaymentRepository->show($id);
        return view('admin.rider-payment.detail', compact('rider_payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rider_payment = $this->riderPaymentRepository->show($id);
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');
        return view('admin.rider-payment.edit', compact('rider_payment', 'riders'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RiderPaymentRequest $request, string $id)
    {
        $rider_payment = $this->riderPaymentRepository->show($id);
        $data = $request->all();
        $this->riderPaymentService->updateRiderPaymentByID($data, $rider_payment);

        return redirect()->route('rider-payments.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->riderPaymentService->deleteRiderPaymentByID($id);
        return redirect()->route('rider-payments.index');
    }

    public function getAjaxRiderPaymentData(Request $request)
    {
        $rider_name = $request->rider_name;
        $data = $this->riderPaymentRepository->getAllRiderPaymentQuery();

        if ($rider_name != null) {
            $data = $data->where('rider_payments.rider_id', $rider_name);
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($rider_payment) {
                $actionBtn = '
                        <a href="' . route("rider-payments.show", $rider_payment->id) . '" class="edit btn btn-info btn-sm">View</a> 
                        <a href="' . route("rider-payments.edit", $rider_payment->id) . '" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="' . route("rider-payments.destroy", $rider_payment->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this rider payment?`);">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-rider_payments.id')
            ->make(true);
    }
}
