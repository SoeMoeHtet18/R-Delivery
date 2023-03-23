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
        if ($request->ajax()) {
            $shop_payments = $this->shopPaymentRepository->getAllShopPaymentsQuery();
            return DataTables::of($shop_payments)
                ->addIndexColumn()
                ->addColumn('action', function($shop_payments){
                    $actionBtn = '
                            <a href="'. route("shoppayments.show", $shop_payments->id) .'" class="edit btn btn-info btn-sm">View</a> 
                            <a href="'. route("shoppayments.edit", $shop_payments->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                            <form action="'.route("shoppayments.destroy", $shop_payments->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this shop user?`);">
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
        return view('admin.shoppayment.index');
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
}