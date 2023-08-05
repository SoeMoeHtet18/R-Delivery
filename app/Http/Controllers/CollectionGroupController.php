<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\CollectionCreateRequest;
use App\Repositories\CollectionGroupRepository;
use App\Repositories\CollectionRepository;
use App\Repositories\CustomerCollectionRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ShopRepository;
use App\Services\CollectionGroupService;
use App\Services\CollectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CollectionGroupController extends Controller
{
    protected $collectionGroupRepository;
    protected $collectionGroupService;
    protected $riderRepository;
    protected $collectionRepository;
    protected $shopRepository;
    protected $customerCollectionRepository;
    protected $collectionService;

    public function __construct(CollectionGroupRepository $collectionGroupRepository, CollectionGroupService $collectionGroupService, RiderRepository $riderRepository, CollectionRepository $collectionRepository, ShopRepository $shopRepository, CustomerCollectionRepository $customerCollectionRepository, CollectionService $collectionService)
    {
        $this->collectionGroupRepository = $collectionGroupRepository;
        $this->collectionGroupService = $collectionGroupService;
        $this->riderRepository = $riderRepository;
        $this->collectionRepository = $collectionRepository;
        $this->shopRepository = $shopRepository;
        $this->customerCollectionRepository = $customerCollectionRepository;
        $this->collectionService = $collectionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.collection-groups.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');
        $shops  = $this->shopRepository->getAllShops();

        $collections = $this->collectionRepository->getAllCollectionsWithoutRider();
        $collections = $collections->sortByDesc('id');

        return view('admin.collection-groups.create', compact('riders', 'collections', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollectionCreateRequest $request)
    {
        $data = $request->all();
        $rider_id = $data['rider_id'];
        $collection_group = $this->collectionGroupService->saveCollectionGroup($data);

        $data_for_collections = json_decode($data['create-collections-data'], true);
        foreach($data_for_collections as $data_for_collection) {
            $this->collectionService->saveCollectionFromGroup($data_for_collection, $rider_id, $collection_group->id);
        } 
        $checkedShopCollections = json_decode($request->input('checked_shop_collections'));
        $checkedCustomerCollections = json_decode($request->input('checked_customer_collections'));
        $data['checkedShopCollections'] = $checkedShopCollections;
        $data['checkedCustomerCollections'] = $checkedCustomerCollections;
        // $this->collectionGroupService->saveCollectionGroupByAdmin($data);
        return redirect()->route('collection-groups.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $collectionGroup = $this->collectionGroupRepository->getCollectionGroupById($id);
        $collections =  $this->collectionRepository->getAllCollectionByCollectionGroupId($id);
        $customer_collections = $this->customerCollectionRepository->getAllCustomerCollectionsByGroupId($id);
        return view('admin.collection-groups.details', compact('collectionGroup', 'collections', 'customer_collections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $collectionGroup = $this->collectionGroupRepository->getCollectionGroupById($id);
        $riders = $this->riderRepository->getAllRiders();
        $riders = $riders->sortByDesc('id');
        $collections = $this->collectionRepository->getAllCollections();
        $collections = $collections->sortByDesc('id');
        $collectionGroupIds = $this->collectionRepository->getAllCollectionByCollectionGroupId($id);

        $date = new Carbon($collectionGroup->assigned_date);
        $assigndate = $date->format('Y-m-d');

        return view('admin.collection-groups.edit', compact('riders', 'collectionGroup', 'collections', 'collectionGroupIds', 'assigndate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $collectionGroup = $this->collectionGroupRepository->getCollectionGroupByID($id);
        $this->collectionGroupService->updateCollectionGroupByAdmin($collectionGroup, $data);

        return redirect()->route('collection-groups.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->collectionGroupService->deleteCollectionGroupByID($id);
        return redirect()->route('collection-groups.index');
    }

    public function getAjaxCollectionGroups()
    {
        $data = $this->collectionGroupRepository->getAllCollectionGroupsQuery();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->orderColumn('id', '-collection_groups.id')
            ->make(true);
    }

    public function getAjaxCollectionGroupsdata()
    {
        $data = $this->collectionGroupRepository->getAllCollectionGroupData();

        return DataTables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route("collection-groups.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("collection-groups.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="'.route("collection-groups.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this city?`);">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('collection_groups.id', '-id $1')
            ->make(true);
    }

    public function getCollectionGroupCode()
    {
        $user = auth()->user();
        $collection_group_code = Helper::nomenclature('collection_groups', 'PG', 'id', $user->branch_id, 'B');
        return response()->json(['data' => $collection_group_code,  'status' => 'success', 'message' => 'Successfully get collection group code'], 200);
    }
}
