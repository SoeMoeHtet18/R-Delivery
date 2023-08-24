<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Collection;
use App\Repositories\CityRepository;
use App\Repositories\CollectionGroupRepository;
use App\Repositories\CollectionRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ShopRepository;
use App\Repositories\TownshipRepository;
use App\Services\CollectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CollectionController extends Controller
{
    protected $collectionRepository;
    protected $collectionService;
    protected $shopRepository;
    protected $riderRepository;
    protected $collectionGroupRepository;

    public function __construct(
        CollectionRepository $collectionRepository,
        CollectionService $collectionService,
        ShopRepository $shopRepository,
        RiderRepository $riderRepository,
        CollectionGroupRepository $collectionGroupRepository)
    {
        $this->collectionRepository = $collectionRepository;
        $this->collectionService    = $collectionService;
        $this->shopRepository = $shopRepository;
        $this->riderRepository = $riderRepository;
        $this->collectionGroupRepository = $collectionGroupRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = $this->collectionRepository->getAllCollections();
        $shops = $this->shopRepository->getAllShops();
        $riders = $this->riderRepository->getAllRiders();
        return view('admin.collections.index', compact('collections', 'shops', 'riders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');

        return view('admin.collections.create', compact('shops', 'riders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->collectionService->saveCollectionData($data);
        return redirect()->route('collections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $collection = $this->collectionRepository->getCollectionByIDWithData($id);
        return view('admin.collections.details', compact('collection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $collection = $this->collectionRepository->getCollectionsByID($id);

        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');
        $assignedAt = new Carbon($collection->assigned_at);
        $assignedAt = $assignedAt->format('Y-m-d');
        $collectedAt = new Carbon($collection->collected_at);
        $collectedAt = $collectedAt->format('Y-m-d');
        $collection_groups = $this->collectionGroupRepository->getAllCollectionGroups();
        $collection_groups = $collection_groups->sortByDesc('id');
        return view('admin.collections.edit', compact('collection','collectedAt','assignedAt', 'shops', 'riders', 'collection_groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $collection = $this->collectionRepository->getCollectionById($id);
        unset($collection->shop_name,$collection->shop_phone_number);
        $data = $request->all();
        $this->collectionService->updateCollectionByID($data, $collection);

        return redirect()->route('collections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->collectionService->deleteCollectionByID($id);
        return redirect()->route('collections.index');
    }

    public function getAjaxCollectionsForShops(Request $request)
    {
        $shop_id = $request->shop_id;
        $data = $this->collectionRepository->getAllCollectionsQueryForShop($shop_id);
        return DataTables::of($data)
            ->addIndexColumn()
            ->orderColumn('id', '-collections.id')
            ->make(true);
    }
    
    public function getCollectionListByRiderId($rider_id)
    {
        $data = $this->collectionRepository->getCollectionsByRiderId($rider_id);
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function getAjaxCollections(Request $request)
    {
        $search         = $request->search;
        $status         = $request->status;
        $rider          = $request->rider;
        $shop           = $request->shop;
        $scheduledAt   = $request->scheduled_at;
        $collectedAt   = $request->collected_at;
        $data = $this->collectionRepository->getAllCollectionsQuery();
        if($search) {
            $data = $data->where('collections.collection_code','like', '%' . $search . '%');
        }
        if($status) {
            $data = $data->where('collections.status',$status);
        }
        if($rider) {
            $data = $data->where('collections.rider_id',$rider);
        }
        if($shop) {
            $data = $data->where('collections.shop_id',$shop);
        }
        if($scheduledAt) {
            $data = $data->whereDate('collections.assigned_at',$scheduledAt);
        }
        if($collectedAt) {
            $data = $data->whereDate('collections.collected_at',$collectedAt);
        }
        return DataTables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route("collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="'.route("collections.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this pick up?`);">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('id', '-collections.id')
            ->make(true);
    }

    public function getCollectionsTableByShopID(Request $request, $id)
    {
       
        $start = $request->from_date;
        $end = $request->to_date . ' 23:59:00';
        $data = $this->collectionRepository->getCollectionsQueryByShopID($id);
        if ($start && $end) {
            $data = $data->whereBetween('collections.created_at', [$start, $end]);
        }
        $collections = $data;
        return DataTables::of($collections)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route("collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="'.route("collections.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this pick up?`);">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-collections.id')
            ->make(true);
    }

    public function getCollectionByRiderID(Request $request, $id)
    {
        $data = $this->collectionRepository->getCollectionsQueryByRiderID($id);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route("collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="'.route("collections.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to delete this pick up?`);">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-collections.id')
            ->make(true);
    }

    public function getCollectionsByGroup(Request $request)
    {
        $collection_group_id = $request['collection_group_id'];
        $data = $this->collectionRepository->getCollectionsQueryByGroupID($collection_group_id);
        return DataTables::of($data)
            ->addIndexColumn()
            ->orderColumn('id', '-collections.id')
            ->make(true);
    }
}
