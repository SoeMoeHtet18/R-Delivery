<?php

namespace App\Http\Controllers;

use App\Repositories\CollectionGroupRepository;
use App\Services\CollectionGroupService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CollectionGroupController extends Controller
{
    protected $collectionGroupRepository;
    protected $collectionGroupService;

    public function __construct(CollectionGroupRepository $collectionGroupRepository, CollectionGroupService $collectionGroupService)
    {
        $this->collectionGroupRepository = $collectionGroupRepository;
        $this->collectionGroupService = $collectionGroupService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->collectionGroupService->saveCollectionGroupByAdmin($data);
        return redirect()->route('collection_groups.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $collectionGroup = $this->collectionGroupRepository->getCollectionGroupByID($id);
        $this->collectionGroupService->updateCollectionGroupByAdmin($collectionGroup, $data);

        return redirect()->route('collection_groups.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->collectionGroupService->deleteCollectionGroupByID($id);
        return redirect()->route('collection_groups.index');
    }

    public function getAjaxCollectionGroups()
    {
        $data = $this->collectionGroupRepository->getAllCollectionGroupsQuery();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->orderColumn('id', '-collection_groups.id')
            ->make(true);
    }
}
