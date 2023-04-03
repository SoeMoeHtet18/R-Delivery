<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionForShopRequest;
use App\Repositories\AdminRepository;
use App\Repositories\ShopRepository;
use App\Repositories\TransactionsForShopRepository;
use App\Services\TransactionsForShopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\Facades\DataTables;

class TransactionsForShopController extends Controller
{   
    protected $transactionsForShopRepository;
    protected $transactionsForShopService;
    protected $shopRepository;
    protected $adminRepository;

    public function __construct(TransactionsForShopRepository $transactionsForShopRepository ,TransactionsForShopService $transactionsForShopService,ShopRepository $shopRepository,AdminRepository $adminRepository)
    {
        $this->transactionsForShopRepository = $transactionsForShopRepository;
        $this->transactionsForShopService = $transactionsForShopService;
        $this->shopRepository = $shopRepository;
        $this->adminRepository = $adminRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shops  = $this->shopRepository->getAllShops();
        $paid_users  = $this->adminRepository->getAllUsers();

        return view('admin.transactionsforshop.index',compact('shops', 'paid_users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        $users = $this->adminRepository->getAllUsers();
        $users = $users->sortByDESC('id');
        return view('admin.transactionsforshop.create', compact('shops', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionForShopRequest $request)
    {   
        $data = $request->all();
        $file = $request->file('image');
        $this->transactionsForShopService->saveTransactionForShopData($data, $file);
        return redirect(route('transactions-for-shop.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction_for_shops = $this->transactionsForShopRepository->getTransactionsForShopByID($id);
        return view('admin.transactionsforshop.detail', compact('transaction_for_shops'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction_for_shop = $this->transactionsForShopRepository->getTransactionsForShopByID($id);

        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        $users = $this->adminRepository->getAllUsers();
        $users = $users->sortByDESC('id');
        
        return view('admin.transactionsforshop.edit',compact('transaction_for_shop','shops','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionForShopRequest $request, string $id)
    {
        $transaction_for_shop = $this->transactionsForShopRepository->getTransactionsForShopByID($id);
        $data = $request->all();
        $file = $request->file('image');
        $this->transactionsForShopService->updateTransactionForShopByID($data, $transaction_for_shop, $file);
        
        return redirect()->route('transactions-for-shop.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->transactionsForShopService->deleteTransactionForShopByID($id);
        return redirect()->route('transactions-for-shop.index');
    }

    public function getAjaxTransactionForShopData(Request $request)
    {   
        $shop_name = $request->shop_name;
        $amount = $request->amount;
        $type = $request->type;
        $paid_by = $request->paid_by;
        $data = $this->transactionsForShopRepository->getAllTransactionsForShopQuery();

        if($shop_name != null) {
            $data = $data->where('transactions_for_shops.shop_id',$shop_name);
        }
        if($amount != null) {
            $data = $data->where('transactions_for_shops.amount',$amount);
        }
        if($type != null) {
            $data = $data->where('transactions_for_shops.type',$type);
        }
        if($paid_by != null) {
            $data = $data->where('transactions_for_shops.paid_by',$paid_by);
        }
        return DataTables::of($data)
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
            ->orderColumn('id','-transactions_for_shops.id')
            ->make(true);
    }

    public function getTransactionsTableByShopID(Request $request, $id)
    {
        $data = $this->transactionsForShopRepository->getTransactionsQueryByShopID($id);
        return DataTables::of($data)
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
            ->orderColumn('id','-transactions_for_shops.id')
            ->make(true);
    }
}
