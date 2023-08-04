<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Collection;
use App\Repositories\CollectionGroupRepository;
use App\Repositories\CollectionRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ShopRepository;
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

    public function __construct(CollectionRepository $collectionRepository, CollectionService $collectionService, ShopRepository $shopRepository,
    RiderRepository $riderRepository,CollectionGroupRepository $collectionGroupRepository)
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
        return view('admin.collections.index', compact('collections'));
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
        $search = $request->search;
        $data = $this->collectionRepository->getAllCollectionsQuery();
        if($search) {
            $data = $data->where('collections.name','like', '%' . $search . '%');
        }
        return DataTables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route("collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="'.route("collections.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this pick up?`);">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('collections.id', '-id $1')
            ->make(true);
    }

    public function getCollectionsTableByShopID(Request $request, $id)
    {
        $data = $this->collectionRepository->getCollectionsQueryByShopID($id);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route("collections.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("collections.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="'.route("collections.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this pick up?`);">
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
                <form action="'.route("collections.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this city?`);">
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
}
