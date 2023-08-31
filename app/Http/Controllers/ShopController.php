<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopCreateRequest;
use App\Http\Requests\ShopUpdateRequest;
use App\Models\Collection;
use App\Models\CustomerCollection;
use App\Models\TransactionsForShop;
use App\Repositories\CollectionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ReportCalculationRepository;
use App\Repositories\ShopRepository;
use App\Repositories\TownshipRepository;
use App\Services\ShopService;
use App\Services\TransactionsForShopService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use Yajra\DataTables\Facades\DataTables;

class ShopController extends Controller
{
    protected $shopRepository;
    protected $shopService;
    protected $orderRepository;
    protected $transactionsForShopService;
    protected $collectionRepository;
    protected $townshipRepository;
    protected $reportCalculationRepository;

    public function __construct(ShopRepository $shopRepository, ShopService $shopService,
        OrderRepository $orderRepository, TransactionsForShopService $transactionsForShopService,
        CollectionRepository $collectionRepository, TownshipRepository $townshipRepository,
        ReportCalculationRepository $reportCalculationRepository)
    {
        $this->shopRepository = $shopRepository;
        $this->shopService = $shopService;
        $this->orderRepository = $orderRepository;
        $this->transactionsForShopService = $transactionsForShopService;
        $this->collectionRepository = $collectionRepository;
        $this->townshipRepository = $townshipRepository;
        $this->reportCalculationRepository = $reportCalculationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.shop.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $townships = $this->townshipRepository->getAllTownships();
        return view('admin.shop.create',compact('townships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShopCreateRequest $request)
    {    
        $data = $request->all();
        $this->shopService->saveShopData($data);
        
        return redirect(route('shops.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shop = $this->shopRepository->getShopByID($id);
        // $order_ids = $this->orderRepository->getAllOrderIdsByShopID($id);
        // $payable_amount = $this->shopService->getPayableAmount($order_ids,$shop->id);
        $payable_amount = $this->reportCalculationRepository->getPayableAmountForShop($shop->id);
        $shop->payable_amount = $payable_amount;
        // $collection_total_amount = Collection::where('shop_id',$id)->where('status','success')->sum('total_amount');
        // $collection_paid_amount = Collection::where('shop_id',$id)->where('status','success')->sum('paid_amount');
        // $remainig_amount  = $collection_total_amount - $collection_paid_amount; 
        return view('admin.shop.detail', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shop = $this->shopRepository->getShopByID($id);
        $townships = $this->townshipRepository->getAllTownships();
        
        return view('admin.shop.edit', compact('shop','townships'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShopUpdateRequest $request, string $id)
    {
        $shop = $this->shopRepository->getShopByID($id);
        $data = $request->all();
        $this->shopService->updateShopByID($data, $shop);
        
        return redirect(route('shops.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->shopService->deleteShopByID($id);
        
        return redirect(route('shops.index'));
    }

    public function getAjaxShopData(Request $request)
    {
        $search = $request->search;
        $from_date = $request->from_date;
        $to_date   = $request->to_date;
        $data = $this->shopRepository->getAllShopsQuery($from_date, $to_date);
        if($search) {
            $data = $data->where('shops.name','like', '%'. $search . '%')
                    ->orWhere('shops.address', 'like', '%'. $search . '%')
                    ->orWhere('shops.phone_number', 'like', '%'. $search . '%');
        }
            return DataTables::of($data)

                ->addIndexColumn()
                ->addColumn('total_ways', function ($shop) {
                    return $shop->orders->count();
                })
                ->addColumn('action', function($shops) {
                    return  '
                        <a href="'. route("shops.show", $shops->id) .'" class="btn btn-info btn-sm">View</a>
                        <a href="'. route("shops.edit", $shops->id) .'" class="btn btn-light btn-sm">Edit</a>
                        <form action="'. route("shops.destroy", $shops->id) .'" method="post" class="d-inline"
                            onclick="return confirm(`Are you sure you want to delete this shop?`);">
                            <input type="hidden" name="_token" value="'. csrf_token() .'"/>
                            <input type="hidden" name="_method" value="DELETE"/>
                            <input type="submit" value="Delete" class="btn btn-danger btn-sm"/>
                        </form>';
                     
                })
                ->rawColumns(['action','total_ways'])
                ->orderColumn('shops.id', '-id $1')
                ->make(true);
    }

    public function getAllCollectionsByShop(Request $request){
        
        $shop_id = $request->shop_id;
        $new_index = $request->new_index;
        $shop = $this->shopRepository->getShopByID($shop_id);
        $shop_collections = Collection::where('shop_id',$shop_id)->whereNull('rider_id')->get();
        $customer_collections = CustomerCollection::where('shop_id',$shop_id)->whereNull('collection_group_id')->get();
        return view('admin.collection-groups.assign_collection',
            compact('shop_collections','customer_collections', 'shop','new_index'));
    }

    public function generateShopPdf(Request $request) {
        try {
            $mpdf = new Mpdf();

            // Enable Myanmar language support
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;

            // Set the font for Myanmar language
            $mpdf->SetFont('myanmar3');

            //retrieve data
            $start = $request->start;
            $end = $request->end;
            $shop_id = $request->shop_id;
            $type = $request->type;

            if($type == 'order') {
                $data = $this->orderRepository->getAllOrdersQueryByShop($shop_id);
                $collectionAmountPaidByCompany = Collection::where('shop_id', $shop_id)->sum('paid_amount');
                $transactionAmountPaidByCompany = TransactionsForShop::where('shop_id',$shop_id)->sum('amount');
            
                $start = $request->from_date;
                $end = $request->to_date . ' 23:59:00';
                if ($start && $end) {
                    $data = $data->whereBetween('orders.created_at', [$start, $end]);
                    $collectionAmountPaidByCompany = Collection::where('shop_id', $shop_id)
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('paid_amount');
                    $transactionAmountPaidByCompany = TransactionsForShop::where('shop_id',$shop_id)
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('amount');
                }

                $orders = $data->get();
                $paidAmount = $collectionAmountPaidByCompany + $transactionAmountPaidByCompany;
                // Add HTML content with Myanmar text
                $mpdf->WriteHTML(view('admin.shop.pdf_export', compact('orders','paidAmount')));
    
                // Output the PDF for download
                $mpdf->Output('shop_order.pdf', 'D');
            } else if($type == 'pick_up') {
                $collections = $this->collectionRepository->getAllCollectionsByShopUser($shop_id);

                $start = $request->from_date;
                $end = $request->to_date . ' 23:59:59'; // Adjusted end time to include the entire day
                if ($start && $end) {
                    $collections = $collections->whereBetween('created_at', [$start, $end]);
                }

                $mpdf->WriteHTML(view('admin.shop.pdf_export_for_pick_up', compact('collections')));
    
                // Output the PDF for download
                $mpdf->Output('shop_pick_up.pdf', 'D');
            } else {
                return redirect()->back()->with('error', "Can't generate pdf");
            }
           
        } catch (Exception $e) {
            return redirect()->back()->with('error', "Can't generate pdf");
        }
    }
    
    public function generateShopsListPdf(Request $request) {
        try {
            $mpdf = new Mpdf();

            // Enable Myanmar language support
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;

            // Set the font for Myanmar language
            $mpdf->SetFont('myanmar3');

            //retrieve data
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $data = $this->shopRepository->getAllShopsQuery($from_date, $to_date);
            $shops = $data->get();
            // Add HTML content with Myanmar text
            $mpdf->WriteHTML(view('admin.shop.pdf_export_for_shops_list', compact('shops')));
    
            // Output the PDF for download
            $mpdf->Output('shops_list.pdf', 'D');

        } catch (Exception $e) {
            return redirect()->back()->with('error', "Can't generate pdf");
        }
    }
}
