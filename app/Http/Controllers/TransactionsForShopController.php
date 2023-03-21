<?php

namespace App\Http\Controllers;

use App\Repositories\ShopRepository;
use App\Repositories\TransactionsForShopRepository;
use App\Services\TransactionsForShopService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionsForShopController extends Controller
{   
    protected $transactionsForShopRepository;
    protected $transactionsForShopService;
    protected $shopRepository;

    public function __construct(TransactionsForShopRepository $transactionsForShopRepository ,TransactionsForShopService $transactionsForShopService,ShopRepository $shopRepository)
    {
        $this->transactionsForShopRepository = $transactionsForShopRepository;
        $this->transactionsForShopService = $transactionsForShopService;
        $this->shopRepository = $shopRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $transaction_for_shops = $this->transactionsForShopRepository->getAllTransactionsForShopByDESC();
            return DataTables::of($transaction_for_shops)
                ->addIndexColumn()
                ->addColumn('action', function($transaction_for_shops){
                    $actionBtn = '
                            <a href="'. route("transactions-for-shop.show", $transaction_for_shops->id) .'" class="edit btn btn-info btn-sm">View</a> 
                            <a href="'. route("transactions-for-shop.edit", $transaction_for_shops->id) .'" class="edit btn btn-light btn-sm">Edit</a> 
                            <form action="'.route("transactions-for-shop.destroy", $transaction_for_shops->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this shop user?`);">
                                <input type="hidden" name="_token" value="'. csrf_token() .'">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                            </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.transactionsforshop.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = $this->shopRepository->getAllShops();
        return view('admin.transactionsforshop.create', compact('shops'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
