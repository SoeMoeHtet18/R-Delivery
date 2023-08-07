<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShopCreateApiRequest;
use App\Models\Collection;
use App\Models\Order;
use App\Repositories\CollectionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ShopRepository;
use App\Repositories\TransactionsForShopRepository;
use App\Services\ShopService;
use Illuminate\Support\Facades\DB;

class ShopApiController extends Controller
{

    protected $shopService;
    protected $shopRepository;
    protected $orderRepository;
    protected $collectionRepository;
    protected $transactionForShopRepository;

    public function __construct(ShopService $shopService, ShopRepository $shopRepository, OrderRepository $orderRepository, CollectionRepository $collectionRepository, TransactionsForShopRepository $transactionForShopRepository)
    {
        $this->shopService = $shopService;
        $this->shopRepository = $shopRepository;
        $this->orderRepository = $orderRepository;
        $this->collectionRepository = $collectionRepository;
        $this->transactionForShopRepository = $transactionForShopRepository;
    }

    public function getAllShopList()
    {
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        return response()->json(['data' => $shops, 'message' => 'Successfully Get Shop List', 'status' => 'success', 200]);
    }

    public function create(ShopCreateApiRequest $request)
    {
        $data = $request->all();
        $shop = $this->shopService->saveShopData($data);

        return response()->json(['data' => $shop, 'message' => 'Successfully Create Shop', 'status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop = $this->shopRepository->getShopByID($shop_user->shop_id);
        $data = $this->shopService->updateShopByID($data, $shop);

        return response()->json(['data' => $data, 'message' => 'Successfully Update Shop', 'status' => 'success'], 200);
    }

    public function getShopDetailInfo()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop = $this->shopRepository->getShopByID($shop_user->shop_id);
        return response()->json(['data' => $shop, 'message' => 'Successfully Get Shop Info', 'status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $shop_id = $request->shop_id;
        $this->shopService->deleteShopByID($shop_id);
        return response()->json(['message' => 'Successfully Delete Shop', 'status' => 'success'], 200);
    }

    public function getPaymentCreditForShop()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id = $shop_user->shop_id;
        $credits = [];

        $total_credit = $this->orderRepository->getTotalCreditForShop($shop_id);
        $credits['total_amount'] = strval($total_credit);

        $paid_credit_from_collection = $this->collectionRepository->getPaidAmountByShopUser($shop_id);
        $paid_credit_from_transaction = $this->transactionForShopRepository->getPaidAmountByShopUser($shop_id);
        $credits['paid_amount'] = strval($paid_credit_from_collection + $paid_credit_from_transaction);

        return response()->json(['data' => $credits, 'message' => 'Successfully get payment credits by shop user', 'status' => 'success'], 200);
    }

    public function getPaymentHistoryForShop()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id = $shop_user->shop_id;

        $payment_history_from_collection = $this->collectionRepository->getPaymentHistoryForShop($shop_id);
        $payment_history_from_transaction = $this->transactionForShopRepository->getPaymentHistoryForShop($shop_id);

        $payment_histories = array_merge($payment_history_from_collection->toArray(), $payment_history_from_transaction->toArray());

        $sorted_payment_histories = collect($payment_histories)->sortByDesc('created_at')->values()->all();

        return response()->json(['data' => $sorted_payment_histories, 'message' => 'Successfully get payment history by shop user', 'status' => 'success'], 200);
    }

    public function getDescriptionForShop(Request $request)
    {
        $shop_id = $request->shop_id;
        // Receivable
        $receivables = Order::where('shop_id', $shop_id)
            ->where('payment_flag', '0')
            ->where('payment_method', 'all_prepaid')
            ->where('payment_channel', 'shop_online_payment')
            ->selectRaw('DATE(created_at) as date, SUM(delivery_fees + extra_charges - discount) as total_receivable')
            ->groupBy('date')
            ->get();

        // Payable
        $payables = Order::where('shop_id', $shop_id)
            ->where('payment_flag', '0')
            ->where('payment_method', 'cash_on_delivery')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total_payable')
            ->groupBy('date')
            ->get();

        // Generate the text report
        $textReport = "";

        if ($payables->isNotEmpty()) {
            $textReport .= "Payable Amounts\n\n";
            foreach ($payables as $record) {
                $date = $record->date;
                $totalPayable = $record->total_payable;
                $textReport .= "$date   $totalPayable MMK\n\n";
            }
            $textReport .= "_________________________________\n";
            $textReport .= "Total Payable   ";
            $textReport .= "{$payables->sum('total_payable')} MMK\n\n";
        }

        if ($receivables->isNotEmpty()) {
            $textReport .= "Receivable Amounts\n\n";
            foreach ($receivables as $record) {
                $date = $record->date;
                $totalReceivable = $record->total_receivable;
                $textReport .= "$date   $totalReceivable MMK\n\n";
            }
            $textReport .= "_________________________________\n";
            $textReport .= "Total Receivable    ";
            $textReport .= "{$receivables->sum('total_receivable')} MMK \n";
        }
        

        return response()->json(['data' => $textReport, 'message' => 'Successfully get description by shop', 'status' => 'success'], 200);
    }
}
