<?php

namespace App\Http\Controllers;

use App\Repositories\PaymentTypeRepository;
use App\Services\PaymentTypeService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentTypeController extends Controller
{
    protected $paymentTypeRepository;
    protected $paymentTypeService;

    public function __construct(PaymentTypeRepository $paymentTypeRepository, PaymentTypeService $paymentTypeService)
    {
        $this->paymentTypeRepository = $paymentTypeRepository;
        $this->paymentTypeService = $paymentTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.payment_type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->paymentTypeService->savePaymentTypeData($data);
        return redirect()->route('payment-types.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment_type = $this->paymentTypeRepository->getPaymentTypeByID($id);
        return view('admin.payment_type.detail', compact('payment_type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment_type = $this->paymentTypeRepository->getPaymentTypeByID($id);
        return view('admin.payment_type.edit', compact('payment_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $payment_type = $this->paymentTypeRepository->getPaymentTypeByID($id);
        $this->paymentTypeService->updatePaymentTypeData($data, $payment_type);
        return redirect()->route('payment-types.show', $payment_type->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->paymentTypeService->deletePaymentTypeData($id);
        return redirect()->route('payment-types.index');
    }

    public function getAjaxPaymentTypeData(Request $request)
    {
        $search = $request->search;
        $data = $this->paymentTypeRepository->getAllPaymentTypeQuery();
        if($search) {
            $data = $data->where('name','like', '%' . $search . '%');
        };

        return DataTables::of($data)
        ->addColumn('action', function($row){
            $actionBtn = '<a href="' . route("payment-types.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
            <a href="' . route("payment-types.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
            <form action="'.route("payment-types.destroy", $row->id) .'" method="post" class="d-inline">
                <input type="hidden" name="_token" value="'. csrf_token() .'">
                <input type="hidden" name="_method" value="DELETE">
                <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
            </form>';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->orderColumn('id','-id $1')
        ->make(true);
    }
}
