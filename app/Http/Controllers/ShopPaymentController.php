<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopPaymentRequest;
use App\Repositories\ShopPaymentRepository;
use App\Repositories\ShopRepository;
use App\Services\ShopPaymentService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShopPaymentController extends Controller
{   
    protected $shopPaymentRepository;
    protected $shopRepository;
    protected $shopPaymentService;
    
    public function __construct(ShopPaymentRepository $shopPaymentRepository, ShopPaymentService $shopPaymentService,ShopRepository $shopRepository)
    {
        $this->shopPaymentRepository = $shopPaymentRepository;
        $this->shopRepository = $shopRepository;
        $this->shopPaymentService = $shopPaymentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $shops = $this->shopRepository->getAllShops();
        return view('admin.shoppayment.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');

        return view('admin.shoppayment.create', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShopPaymentRequest $request)
    {   
        $data = $request->all();
        $file = $request->file('image');
        $this->shopPaymentService->saveShopPaymentData($data, $file);
        return redirect(route('shoppayments.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $shop_payment = $this->shopPaymentRepository->getShopPaymentByID($id);
        return view('admin.shoppayment.detail', compact('shop_payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        $shop_payment = $this->shopPaymentRepository->getShopPaymentByID($id);

        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        
        return view('admin.shoppayment.edit',compact('shop_payment','shops'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShopPaymentRequest $request, string $id)
    {   
        
        $shop_payment = $this->shopPaymentRepository->getShopPaymentByID($id);
        $data = $request->all();
        $file = $request->file('image');
        $this->shopPaymentService->updateShopPaymentByID($data, $shop_payment, $file);
        return redirect()->route('shoppayments.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->shopPaymentService->deleteShopPaymentByID($id);
        return redirect()->route('shoppayments.index');
    }

    public function getAjaxShopPaymentData(Request $request)
    {
        $item_type     = $request->item_type;
        $shop_name     = $request->shop_name;
        $amount        = $request->amount;
        $shop_payments = $this->shopPaymentRepository->getAllShopPaymentsQuery();
        if($item_type != null) {
            $shop_payments = $shop_payments->where('type',$item_type);
        }
        if($shop_name != null) {
            $shop_payments = $shop_payments->where('shop_id',$shop_name);
        }
        if($amount != null) {
            $shop_payments = $shop_payments->where('amount',$amount);
        }
        return DataTables::of($shop_payments)
            ->addIndexColumn()
            ->addColumn('action', function($shop_payments){
                $actionBtn = '
                        <a href="'. route("shoppayments.show", $shop_payments->id) .'" class="edit btn btn-info btn-sm">View</a> 
                        <a href="'. route("shoppayments.edit", $shop_payments->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="'.route("shoppayments.destroy", $shop_payments->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this payment?`);">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-shop_payments.id')
            ->make(true);
    }

    public function getShopPaymentTableByShopID($id)
    {
        $data = $this->shopPaymentRepository->getShopPaymentQueryByShopID($id);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($shop_payments){
                $actionBtn = '
                        <a href="'. route("shoppayments.show", $shop_payments->id) .'" class="edit btn btn-info btn-sm">View</a> 
                        <a href="'. route("shoppayments.edit", $shop_payments->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="'.route("shoppayments.destroy", $shop_payments->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this payment?`);">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-shop_payments.id')
            ->make(true);
    }

    public function createShopPaymentByShopID(Request $request)
    {
        $shop_id = $request->shop_id;
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        return view('admin.shoppayment.create', compact('shop_id','shops'));
    }
}