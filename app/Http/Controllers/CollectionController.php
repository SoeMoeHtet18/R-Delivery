<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Repositories\CollectionRepository;
use App\Services\CollectionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CollectionController extends Controller
{
    protected $collectionRepository;
    protected $collectionService;

    public function __construct(CollectionRepository $collectionRepository, CollectionService $collectionService)
    {
        $this->collectionRepository = $collectionRepository;
        $this->collectionService    = $collectionService;
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        //
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
}
