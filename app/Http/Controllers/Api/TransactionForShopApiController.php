<?php

namespace App\Http\Controllers;

use App\Models\TransactionsForShop;
use App\Repositories\ShopRepository;
use App\Repositories\TransactionsForShopRepository;
use Illuminate\Http\Request;

class TransactionForShopApiController extends Controller
{
    protected $transactionsForShopRepository;
    protected $shopRepository;

    public function __construct(TransactionsForShopRepository $transactionsForShopRepository, ShopRepository $shopRepository)
    {
        $this->transactionsForShopRepository = $transactionsForShopRepository;
        $this->shopRepository = $shopRepository;
    }

    public function getTransactionForShopListByShopID($id)
    {
        $shop_id = $this->shopRepository->getShopIDByShopUserID($id);
        $transactions_for_shop = $this->transactionsForShopRepository->getTransactionsForShopListByShopID($shop_id);
        return response()->json(['data' => $transactions_for_shop, 'message' => 'Successfully Get Transactions For Shop By Shop ID','status'=> 'success', 200]);
    }

    public function getTransactionForShopDetailByID(Request $request)
    {
        $id = $request->id;
        $transactions_for_shop = $this->transactionsForShopRepository->getTransactionsForShopDetailByID($id);
        return response()->json(['data' => $transactions_for_shop, 'message' => 'Successfully Get Transactions For Shop Detail By ID','status'=> 'success', 200]);
    }
}
