<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchAssignToRiderRequest;
use App\Http\Requests\GateAssignToRiderRequest;
use App\Http\Requests\RiderCreateRequest;
use App\Http\Requests\RiderUpdateRequest;
use App\Http\Requests\ThirdPartyVendorAssignToRiderRequest;
use App\Http\Requests\ThirdPartyVendorRequest;
use App\Http\Requests\TownshipAssignRequest;
use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\CustomerCollection;
use App\Models\Order;
use App\Models\Rider;
use App\Repositories\BranchRepository;
use App\Repositories\CollectionRepository;
use App\Repositories\DeficitRepository;
use App\Repositories\GateRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ThirdPartyVendorRepository;
use App\Repositories\TownshipRepository;
use App\Services\DeficitService;
use App\Services\RiderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use Yajra\DataTables\Facades\DataTables;

class RiderController extends Controller
{
    protected $riderRepository;
    protected $riderService;
    protected $townshipRepository;
    protected $deficitRepository;
    protected $deficitService;
    protected $orderRepository;
    protected $collectionRepository;
    protected $branchRepository;
    protected $gateRepository;
    protected $thirdPartyVendorRepository;

    public function __construct(RiderRepository $riderRepository, RiderService $riderService,
        TownshipRepository $townshipRepository, DeficitRepository $deficitRepository,
        DeficitService $deficitService, OrderRepository $orderRepository,
        CollectionRepository $collectionRepository, BranchRepository $branchRepository,
        GateRepository $gateRepository, ThirdPartyVendorRepository $thirdPartyVendorRepository)
    {
        $this->riderRepository = $riderRepository;
        $this->riderService = $riderService;
        $this->townshipRepository = $townshipRepository;
        $this->deficitRepository = $deficitRepository;
        $this->deficitService = $deficitService;
        $this->orderRepository = $orderRepository;
        $this->collectionRepository = $collectionRepository;
        $this->branchRepository = $branchRepository;
        $this->gateRepository = $gateRepository;
        $this->thirdPartyVendorRepository = $thirdPartyVendorRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
        $today = Carbon::today()->format('Y-m-d');
        $deliveredOrders = Order::where(['status' => 'success', 'schedule_date' => $today, 'rider_id' =>$id])->get();
        $cashToCollect = 0;
        foreach ($deliveredOrders as $order) {
            $deliveryCharges = $order->delivery_fees + $order->markup_delivery_fees + $order->extra_charges;
            $discount = $order->discount;
            if ($order->payment_method === 'cash_on_delivery') {
                $cashToCollect += $order->total_amount + $deliveryCharges - $discount;
            } elseif ($order->payment_method === 'item_prepaid') {
                $cashToCollect += $deliveryCharges - $discount;
            }
        }
        $pickUpGroupTotalAmount = CollectionGroup::where('rider_id', $id)->whereDate('assigned_date', $today)->sum('total_amount');
        $pickUpPaidAmount = Collection::where('rider_id', $id)->whereDate('collected_at', $today)->sum('paid_amount');
        $customerExchangePaidAmount = CustomerCollection::with('collection_group')
                    ->whereHas('collection_group', function($q) use ($today){
                        $q->where('assigned_date',$today);
                    })
                    ->where('rider_id', $id)->sum('paid_amount');
        $paidAmountToRider   = $cashToCollect + $pickUpGroupTotalAmount;
        $paidAmountFromRider = $pickUpPaidAmount + $customerExchangePaidAmount;

        return view('admin.rider.detail', compact('rider', 'townships', 'paidAmountToRider', 'paidAmountFromRider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $townships = $this->townshipRepository->getAllTownships();
        return view('admin.rider.edit', compact('rider', 'townships'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RiderUpdateRequest $request, string $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $data = $request->all();
        $this->riderService->updateRiderByID($data, $rider);

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
        $townships = $this->townshipRepository->getTownshipsWithoutAssociable();
        // $townships = $townships->sortByDesc('id');
        $rider = $this->riderRepository->getRiderByID($id);
        return view('admin.rider.assign_township', compact('rider', 'townships'));
    }

    public function assignTownshipToRider(TownshipAssignRequest $request, $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $data = $request->all();
        $this->riderService->assignTownship($rider, $data);
        return redirect(route('riders.show', $id));
    }

    public function getAjaxRiderData(Request $request)
    {
        $search = $request->search;

        $data = $this->riderRepository->getAllRidersQuery();
        if ($search) {
            $data = $data->where('riders.name', 'like', '%' . $search . '%')->orWhere('riders.phone_number', 'like', '%' . $search . '%')->orWhere('riders.email', 'like', '%' . $search . '%');
        }
        return DataTables::of($data)

            ->addIndexColumn()
            ->addColumn('action', function ($riders) {
                $actionBtn = '
                        <a href="' . route("riders.show", $riders->id) . '" class="edit btn btn-info btn-sm">View</a> 
                        <a href="' . route("riders.edit", $riders->id) . '" class="edit btn btn-light btn-sm">Edit</a> 
                        <form action="' . route("riders.destroy", $riders->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this rider?`);">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-id $1')
            ->make(true);
    }

    public function addDeficitToRider(Request $request)
    {
        $data = $request->all();
        $this->deficitService->addDeficitToRider($data);
        return redirect(route('riders.show', $data['rider_id']));
    }

    public function getDeficitByRider($id)
    {
        $data = $this->deficitRepository->getDeficitByRiderId($id);
       
        return DataTables::of($data)
            ->addIndexColumn()
            ->orderColumn('id', '-id $1')
            ->make(true);
    }
    
    public function getRiderByType(Request $request)
    {
        $type = $request->type;
        $riders = $this->riderRepository->getRiderBySalaryType($type);
       
        return response()->json(['data' => $riders, 'message' => 'Successfully Get Rider By Salary Type', 'status' => 'success'], 200);
    }
    
    public function getRiderTotalSalaryByDate(Request $request)
    {
        $rider_id = $request->rider_id;
        $monthly    = $request->monthly;
        $daily    = $request->daily;
        $rider = $this->riderRepository->getRiderByID($rider_id);
        $data = $this->riderRepository->getTotalSalaryByRider($rider,$daily,$monthly);
        
        return response()->json(['data' => $data, 'message' => 'Successfully Get Total Salary for Rider', 'status' => 'success'], 200);
       
    }
    
    public function getRidersByTownship(Request $request)
    {
        $township_id = $request->township_id;
        $data = $this->riderRepository->getRiderByTownship($township_id);
        
        return response()->json(['data' => $data, 'message' => 'Successfully Get Total Salary for Rider', 'status' => 'success'], 200);
       
    }

    public function generateRiderPdf(Request $request)
    {
        try {
            $mpdf = new Mpdf();

            // Enable Myanmar language support
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;

            // Set the font for Myanmar language
            $mpdf->SetFont('myanmar3');

            //retrieve data
            $rider_id = $request->rider_id;
            $type = $request->type;

            if($type == 'order') {
                $orders = $this->orderRepository->getTodayOrdersByRider($rider_id);
            
                // Add HTML content with Myanmar text
                $mpdf->WriteHTML(view('admin.rider.pdf_export', compact('orders')));
    
                // Output the PDF for download
                $mpdf->Output('rider_order.pdf', 'D');
            } elseif($type == 'pick_up') {
                $collections = $this->collectionRepository->getAssignedCollectionByRiderForToday($rider_id);
                
                // Add HTML content with Myanmar text
                $mpdf->WriteHTML(view('admin.rider.pdf_export_for_pick_up', compact('collections')));
    
                // Output the PDF for download
                $mpdf->Output('rider_pick_up.pdf', 'D');
            } else {
                return redirect()->back()->with('error', "Can't generate pdf");
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', "Can't generate pdf");
        }
    }

    public function assignBranch($id)
    {
        $branches = $this->branchRepository->getAllData();
        $rider = $this->riderRepository->getRiderByID($id);
        return view('admin.rider.assign_branch', compact('rider', 'branches'));
    }

    public function assignBranchToRider(BranchAssignToRiderRequest $request, $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $data = $request->all();
        // dd($data);
        $this->riderService->assignBranch($rider, $data);
        return redirect(route('riders.show', $id));
    }
    
    public function assignGate($id)
    {
        $gates = $this->gateRepository->getAllData();
        $rider = $this->riderRepository->getRiderByID($id);
        return view('admin.rider.assign_gate', compact('rider', 'gates'));
    }

    public function assignGateToRider(GateAssignToRiderRequest $request, $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $data = $request->all();
        $this->riderService->assignGate($rider, $data);
        return redirect(route('riders.show', $id));
    }
    
    public function assignThirdPartyVendor($id)
    {
        $thirdPartyVendors = $this->thirdPartyVendorRepository->getAllData();
        $rider = $this->riderRepository->getRiderByID($id);
        return view('admin.rider.assign_third_party_vendor', compact('rider', 'thirdPartyVendors'));
    }

    public function assignThirdPartyVendorToRider(ThirdPartyVendorAssignToRiderRequest $request, $id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $data = $request->all();
        $this->riderService->assignThirdPartyVendor($rider, $data);
        return redirect(route('riders.show', $id));
    }
    
    public function getAllAssignTownshipsByRider($id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        $data  = DB::table('rider_township')->where('rider_id',$id)
            ->leftJoin('townships','rider_township.township_id','townships.id')
            ->select('rider_township.*','townships.name as township_name')
            ->orderBy('rider_township.id','desc');
        
        return DataTables::of($data)

            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $actionBtn = '
                        <form action="' . url("delete-rider-assign-township-by-id/". $data->id) . '" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to remove this assigned township for rider?`);">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('rider_township.id', '-id $1')
            ->make(true);
    }

    public function deleteRiderAssignTownship(string $id)
    {
        DB::table('rider_township')->where('id', $id)->delete();
        return redirect()->back();
    }
}
