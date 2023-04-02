<?php

namespace App\Http\Controllers;

use App\Models\TransactionsForShop;
use App\Repositories\AdminRepository;
use App\Repositories\CityRepository;
use App\Repositories\CustomerPaymentRepository;
use App\Repositories\ItemTypeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RiderRepository;
use App\Repositories\ShopPaymentRepository;
use App\Repositories\ShopRepository;
use App\Repositories\ShopUserRepository;
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
        OrderRepository $orderRepository)
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

        // dd($usercount);
        return view('admin.dashboard',compact('usercount','shopcount','shopusercount','citycount','itemTypeCount','townshipcount','shoppaymentcount','customerpaymentcount','transactionforshopcount','ridercount','ordercount'));
    }
}
