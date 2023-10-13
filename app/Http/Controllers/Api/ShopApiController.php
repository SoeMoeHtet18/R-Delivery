<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShopCreateApiRequest;
use App\Http\Requests\ShopCreateRequest;
use App\Http\Requests\ShopUpdateApiRequest;
use App\Models\Collection;
use App\Models\Order;
use App\Repositories\CollectionRepository;
use App\Repositories\CustomerCollectionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ReportCalculationRepository;
use App\Repositories\ShopPaymentRepository;
use App\Repositories\ShopRepository;
use App\Repositories\ShopUserRepository;
use App\Repositories\TransactionsForShopRepository;
use App\Services\ShopService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class ShopApiController extends Controller
{

    protected $shopService;
    protected $shopRepository;
    protected $orderRepository;
    protected $collectionRepository;
    protected $transactionForShopRepository;
    protected $reportCalculationRepository;
    protected $shopUserRepository;
    protected $customerCollectionRepository;
    protected $shopPaymentRepository;

    public function __construct(ShopService $shopService, ShopRepository $shopRepository,
        OrderRepository $orderRepository, CollectionRepository $collectionRepository,
        TransactionsForShopRepository $transactionForShopRepository,
        ReportCalculationRepository $reportCalculationRepository, ShopUserRepository $shopUserRepository,
        CustomerCollectionRepository $customerCollectionRepository, ShopPaymentRepository $shopPaymentRepository)
    {
        $this->shopService = $shopService;
        $this->shopRepository = $shopRepository;
        $this->orderRepository = $orderRepository;
        $this->collectionRepository = $collectionRepository;
        $this->transactionForShopRepository = $transactionForShopRepository;
        $this->reportCalculationRepository = $reportCalculationRepository;
        $this->shopUserRepository = $shopUserRepository;
        $this->customerCollectionRepository = $customerCollectionRepository;
        $this->shopPaymentRepository = $shopPaymentRepository;
    }

    public function getAllShopList()
    {
        $shops = $this->shopRepository->getAllShops();
        return response()->json([
            'data' => $shops,
            'message' => 'Successfully Get Shop List',
            'status' => 'success', 200]);
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
        return response()->json([
            'data' => $shop,
            'message' => 'Successfully Get Shop Info',
            'status' => 'success'], 200);
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

        $total_credit = $this->reportCalculationRepository->getTotalCreditForShop($shop_id);
        $credits['total_amount'] = $total_credit;

        $paid_credit_from_collection = $this->collectionRepository->getPaidAmountByShopUser($shop_id);
        $paid_credit_from_transaction = $this->transactionForShopRepository->getPaidAmountByShopUser($shop_id);
        $credits['paid_amount'] = strval($paid_credit_from_collection + $paid_credit_from_transaction);

        return response()->json(['data' => $credits, 'message' =>
            'Successfully get payment credits by shop user', 'status' => 'success'], 200);
    }

    public function getPaymentHistoryForShop()
    {
        $shop_user = auth()->guard('shop-user-api')->user();
        $shop_id = $shop_user->shop_id;

        $payment_history_from_collection = $this->collectionRepository->getPaymentHistoryForShop($shop_id);
        $payment_history_from_transaction = $this->transactionForShopRepository->getPaymentHistoryForShop($shop_id);

        $payment_histories = array_merge($payment_history_from_collection->toArray(),
            $payment_history_from_transaction->toArray());

        $sorted_payment_histories = collect($payment_histories)->sortByDesc('created_at')->values()->all();

        return response()->json([
            'data' => $sorted_payment_histories,
            'message' => 'Successfully get payment history by shop user',
            'status' => 'success'], 200);
    }

    public function getDescriptionForShop(Request $request)
    {
        $shopId = $request->shop_id;
        $start = DateTime::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d 00:00:00');
        $end = DateTime::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d 23:59:59');

        $receivables = $this->reportCalculationRepository
            ->getPayableAndReceivableAmountsForShopByDate($shopId, $start, $end, 'receivable');
        $payables = $this->reportCalculationRepository
            ->getPayableAndReceivableAmountsForShopByDate($shopId, $start, $end, 'payable');

        $textReport = $this->generateTextReport($receivables, 'Receivable Amounts');
        
        $textReport .= $this->generateTextReport($payables, 'Payable Amounts');

        return response()->json([
            'data' => $textReport,
            'message' => 'Successfully get description by shop',
            'status' => 'success'
        ], 200);
    }

    private function generateTextReport($data, $title)
    {
        $textReport = '';

        if ($data->isNotEmpty()) {
            $textReport .= "$title\n\n";
            foreach ($data as $record) {
                if ($record->total_amount > 0) {
                    $date = $record->date;
                    $totalAmount = $record->total_amount;
                    $totalAmount = number_format($totalAmount, 2, '.', ',');
                    $textReport .= "$date   $totalAmount MMK\n\n";
                }
            }
            $textReport .= "_________________________________\n";
            $textReport .= "Total $title    ";
            $textReport .= number_format($data->sum('total_amount'), 2, '.', ',') . " MMK\n\n";
        }

        return $textReport;
    }

    public function getShopTableData(Request $request)
    {
        $search = $request->search;
        $from_date = $request->from_date;
        $to_date   = $request->to_date;
        $data = $this->shopRepository->getAllShopData($request, $search, $from_date, $to_date);
        return response()->json([
            'data' => $data,
            'message' => 'Successfully get shop table data',
            'status' => 'success'
        ], 200);
    }

    public function store(ShopCreateRequest $request)
    {
        $data = $request->all();
        $shop = $this->shopService->saveShopData($data);
        
        return response()->json([
            'data' => $shop,
            'message' => 'Successfully created shop',
            'status' => 'success'
        ], 200);
    }
    
    public function getShopDetail($id)
    {
        $shop = $this->shopRepository->getShopDetailByID($id);
        $shop = $this->getShopFinancialAmounts($shop, $id);

        return response()->json([
            'data' => $shop,
            'message' => 'Successfully get shop detail',
            'status' => 'success'
        ], 200);
    }

    public function updateShopDetail(Request $request, $id)
    {
        $shop = $this->shopRepository->getShopByID($id);
        $data = $request->all();
        $this->shopService->updateShopByID($data, $shop);
        $updatedShop = $this->shopRepository->getShopDetailByID($id);
        $updatedShop = $this->getShopFinancialAmounts($updatedShop, $id);

        return response()->json([
            'data' => $updatedShop,
            'message' => 'Successfully updated shop detail',
            'status' => 'success'
        ], 200);
    }

    private function getShopFinancialAmounts($shop, $id)
    {
        $payable_amount = $this->reportCalculationRepository->getPayableAmountForShop($id);
        $total_credit = $this->reportCalculationRepository->getTotalCreditForShop($id);

        $paid_credit_from_collection = $this->collectionRepository->getPaidAmountByShopUser($id);
        $paid_credit_from_transaction = $this->transactionForShopRepository->getPaidAmountByShopUser($id);

        $shop->payable_amount = $payable_amount;
        $shop->total_credit = $total_credit;
        $shop->paid_amount = $paid_credit_from_collection + $paid_credit_from_transaction;
        return $shop;
    }


    public function getShopUsers($id)
    {
        $shop_users = $this->shopUserRepository->getShopUsersByShopID($id);
        return response()->json([
            'data' => $shop_users,
            'message' => 'Successfully get shop users',
            'status' => 'success'
        ], 200);
    }

    public function getShopOrders(Request $request, $id)
    {
        $status = $request->status;
        $start = $request->from_date;
        $end = $request->to_date;
         
        $shop_orders = $this->orderRepository->getShopOrdersByShopID($id, $status, $start, $end);

        return response()->json([
            'data' => $shop_orders,
            'message' => 'Successfully get shop orders',
            'status' => 'success'
        ], 200);
    }

    public function getAmountsRelatedToOrder(Request $request)
    {
        $amounts = $this->orderRepository->getAmountsRelatedToOrder($request);
        
        return response()->json([
            'data' => $amounts,
            'message' => 'Successfully get related amounts',
            'status' => 'success'], 200);
    }

    public function getShopPickUps(Request $request, $id)
    {
        $start = $request->from_date;
        $end = $request->to_date;

        $pickUps = $this->collectionRepository->getShopPickUpsByShopID($id, $start, $end);

        return response()->json([
            'data' => $pickUps,
            'message' => 'Successfully get shop pick ups',
            'status' => 'success'], 200);
    }

    public function getShopExchanges(Request $request, $id)
    {
        $start = $request->from_date;
        $end = $request->to_date;
        
        $exchanges = $this->customerCollectionRepository->getShopExchangesByShopID($id, $start, $end);

        return response()->json([
            'data' => $exchanges,
            'message' => 'Successfully get shop exchanges',
            'status' => 'success'], 200);
    }

    public function getShopPayments(Request $request, $id)
    {
        $start = $request->from_date;
        $end = $request->to_date;
        
        $payments = $this->shopPaymentRepository->getShopPaymentListByShopID($id, $start, $end);

        return response()->json([
            'data' => $payments,
            'message' => 'Successfully get shop payments',
            'status' => 'success'], 200);
    }
    
    public function getShopTransactions(Request $request, $id)
    {
        $start = $request->from_date;
        $end = $request->to_date;
        
        $transactions = $this->transactionForShopRepository->getShopTransactionsByShopID($id, $start, $end);

        return response()->json([
            'data' => $transactions,
            'message' => 'Successfully get shop payments',
            'status' => 'success'], 200);
    }

    public function downloadShopPdf(Request $request, $id) {
        try {
            $mpdf = new Mpdf();

            // Enable Myanmar language support
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;

            // Set the font for Myanmar language
            $mpdf->SetFont('myanmar3');

            //retrieve data
            $status = $request->status;
            $start = $request->from_date;
            $end = $request->to_date;
            $shop_id = $id;
            $type = $request->type;

            if($type == 'order') {
                $orders = $this->orderRepository->getShopOrdersByShopID($shop_id, $status, $start, $end);
                $paidAmount = $this->shopRepository->getAllPaidAmountByCompany($shop_id, $start, $end);
               
                // Add HTML content with Myanmar text
                $mpdf->WriteHTML(view('admin.shop.pdf_export', compact('orders','paidAmount')));
    
                // Output the PDF for download
                $mpdf->Output('shop_order.pdf', 'D');
            } elseif($type == 'pick_up') {
                $collections = $this->collectionRepository->getShopPickUpsByShopID($id, $start, $end);

                $mpdf->WriteHTML(view('admin.shop.pdf_export_for_pick_up', compact('collections')));
    
                // Output the PDF for download
                $mpdf->Output('shop_pick_up.pdf', 'D');
            } else {
                return redirect()->back()->with('error', "Can't generate pdf");
            }
           
        } catch (Exception $e) {
            return redirect()->back()->with('error', "Can't generate pdf");
        }
    }
}
