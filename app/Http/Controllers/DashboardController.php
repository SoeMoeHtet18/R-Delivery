<?php

namespace App\Http\Controllers;

use App\Models\TransactionsForShop;
use App\Repositories\AdminRepository;
use App\Repositories\BranchRepository;
use App\Repositories\CityRepository;
use App\Repositories\CollectionGroupRepository;
use App\Repositories\CollectionRepository;
use App\Repositories\CustomerCollectionRepository;
use App\Repositories\CustomerPaymentRepository;
use App\Repositories\DeliveryTypesRepository;
use App\Repositories\GateRepository;
use App\Repositories\ItemTypeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RiderPaymentRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ShopPaymentRepository;
use App\Repositories\ShopRepository;
use App\Repositories\ShopUserRepository;
use App\Repositories\ThirdPartyVendorRepository;
use App\Repositories\TownshipRepository;
use App\Repositories\TransactionsForShopRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $adminRepository;
    protected $shopRepository;
    protected $shopUserRepository;
    protected $cityRepository;
    protected $itemTypeRepository;
    protected $townshipRepository;
    protected $shopPaymentRepository;
    protected $customerPaymentRepository;
    protected $transactionsForShopRepository;
    protected $riderRepository;
    protected $orderRepository;
    protected $riderPaymentRepository;
    protected $collectionRepository;
    protected $customerCollectionRepository;
    protected $collectionGroupRepository;
    protected $branchRepository;
    protected $gateRepository;
    protected $thirdPartyVentorRepository;
    protected $deliveryTypeRepository;

    public function __construct(
        AdminRepository $adminRepository,
        ShopRepository $shopRepository,
        ShopUserRepository $shopUserRepository,
        CityRepository $cityRepository,
        ItemTypeRepository $itemTypeRepository,
        TownshipRepository $townshipRepository,
        ShopPaymentRepository $shopPaymentRepository,
        CustomerPaymentRepository $customerPaymentRepository,
        TransactionsForShopRepository $transactionsForShopRepository,
        RiderRepository $riderRepository,
        OrderRepository $orderRepository,
        RiderPaymentRepository $riderPaymentRepository,
        CollectionRepository $collectionRepository,
        CustomerCollectionRepository $customerCollectionRepository,
        CollectionGroupRepository $collectionGroupRepository,
        BranchRepository $branchRepository,
        GateRepository $gateRepository,
        ThirdPartyVendorRepository $thirdPartyVentorRepository,
        DeliveryTypesRepository $deliveryTypeRepository,
    )
    {
        $this->adminRepository = $adminRepository;
        $this->shopRepository  = $shopRepository;
        $this->shopUserRepository = $shopUserRepository;
        $this->cityRepository = $cityRepository;
        $this->itemTypeRepository = $itemTypeRepository;
        $this->townshipRepository = $townshipRepository;
        $this->shopPaymentRepository = $shopPaymentRepository;
        $this->customerPaymentRepository = $customerPaymentRepository;
        $this->transactionsForShopRepository = $transactionsForShopRepository;
        $this->riderRepository = $riderRepository;
        $this->orderRepository = $orderRepository;
        $this->riderPaymentRepository = $riderPaymentRepository;
        $this->collectionRepository = $collectionRepository;
        $this->customerCollectionRepository = $customerCollectionRepository;
        $this->collectionGroupRepository = $collectionGroupRepository;
        $this->branchRepository = $branchRepository;
        $this->gateRepository = $gateRepository;
        $this->thirdPartyVentorRepository = $thirdPartyVentorRepository;
        $this->deliveryTypeRepository = $deliveryTypeRepository;
    }

    public function count()
    {
        $usercount = $this->adminRepository->getAllUsersCount();
        $shopcount = $this->shopRepository->getAllShopCount();
        $shopusercount = $this->shopUserRepository->getAllShopUserCount();
        $citycount = $this->cityRepository->getAllCityCount();
        $itemTypeCount = $this->itemTypeRepository->getAllItemTypesCount();
        $townshipcount = $this->townshipRepository->getAllTownshipsCount();
        $shoppaymentcount = $this->shopPaymentRepository->getAllShopPaymentCount();
        $customerpaymentcount = $this->customerPaymentRepository->getAllCustomerPaymentCount();
        $transactionforshopcount = $this->transactionsForShopRepository->getAllTransactionsForShopCount();
        $ridercount = $this->riderRepository->getAllRidersCount();
        $ordercount = $this->orderRepository->getAllOrdersCount();
        $riderPaymentCount = $this->riderPaymentRepository->getAllRiderPaymentCount();
        $collectionCount = $this->collectionRepository->getAllCollectionCount();
        $customerCollectionCount = $this->customerCollectionRepository->getAllCustomerCollectionCount();
        $collectionGroupCount = $this->collectionGroupRepository->getAllCollectionGroupCount();
        $branchCount = $this->branchRepository->getAllBranchCount();
        $gateCount = $this->gateRepository->getAllGateCount();
        $thirdPartyVendorCount = $this->thirdPartyVentorRepository->getAllThirdPartyVendorCount();
        $deliveryTypeCount = $this->deliveryTypeRepository->getAllDeliveryTypeCount();

        // dd($usercount);
        return view('admin.dashboard_new',compact('usercount','shopcount','shopusercount','citycount',
            'itemTypeCount','townshipcount','shoppaymentcount','customerpaymentcount', 'riderPaymentCount',
            'transactionforshopcount','ridercount','ordercount', 'collectionCount', 'customerCollectionCount',
            'collectionGroupCount', 'branchCount', 'gateCount', 'thirdPartyVendorCount', 'deliveryTypeCount'));
    }

    public function index()
    {
        return view('vue-pages.dashboard.browse');
    }
}
