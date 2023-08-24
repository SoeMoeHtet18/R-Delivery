<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerPaymentRequest;
use App\Models\CustomerPayment;
use App\Repositories\CustomerPaymentRepository;
use App\Repositories\OrderRepository;
use App\Services\CustomerPaymentService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CustomerPaymentController extends Controller
{

    protected $orderRepository;
    protected $customerPaymentService;
    protected $customerPaymentRepository;

    public function __construct(OrderRepository $orderRepository, CustomerPaymentService $customerPaymentService, CustomerPaymentRepository $customerPaymentRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->customerPaymentService = $customerPaymentService;
        $this->customerPaymentRepository = $customerPaymentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = $this->orderRepository->getAllOrders();
        return view('admin.customerpayment.index',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = $this->orderRepository->getAllOrders();
        $orders = $orders->sortByDesc('id');
        
        return view('admin.customerpayment.create',compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerPaymentRequest $request)
    { 
        $data = $request->all();
        $file = $request->file('proof_of_payment');
        $this->customerPaymentService->saveCustomerPayment($data, $file);
        
        return redirect()->route('customer-payments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer_payment = $this->customerPaymentRepository->getCustomerPaymentByID($id);
        return view('admin.customerpayment.detail', compact('customer_payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $orders = $this->orderRepository->getAllOrders();
        $orders = $orders->sortByDesc('id');
        $customer_payment = $this->customerPaymentRepository->getCustomerPaymentByID($id);
        $paid_at = null;
        if($customer_payment->paid_at) {
            $datetime = new DateTime($customer_payment->paid_at);
            $paid_at = $datetime->format('Y-m-d');
        }
        return view('admin.customerpayment.edit',compact('orders','customer_payment','paid_at'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerPaymentRequest $request, string $id)
    {  
        $data = $request->all();
        $file = $request->file('proof_of_payment');
        $customer_payment = $this->customerPaymentRepository->getCustomerPaymentByID($id);
        $this->customerPaymentService->updateCustomerPaymentByID($data,$customer_payment, $file);
        
        return redirect()->route('customer-payments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->customerPaymentService->deleteCustomerPaymentByID($id);
        return redirect()->route('customer-payments.index');
    }

    public function getAjaxCustomerPaymentData(Request $request)
    {
        $item_type     = $request->item_type;
        $order_code     = $request->order_code;
        $amount        = $request->amount;
        $data = $this->customerPaymentRepository->getAllCustomerPaymentsQuery();
        if($item_type != null) {
            $data = $data->where('customer_payments.type',$item_type);
        }
        if($order_code != null) {
            $data = $data->where('customer_payments.order_id',$order_code);
        }
        if($amount != null) {
            $data = $data->where('customer_payments.amount',$amount);
        }
        return DataTables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route("customer-payments.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("customer-payments.edit", $row->id) . '" class="edit btn btn-light btn-sm" >Edit</a>
                <form action="'.route("customer-payments.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this customer payment?`);">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('id','-customer_payments.id')
            ->make(true);
        
    }
}
