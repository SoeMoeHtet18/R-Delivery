<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;

class PaymentNotificationListController extends Controller
{
    protected $shopRepository;

    public function __construct(
        ShopRepository $shopRepository,
    ) {
        $this->shopRepository = $shopRepository;
    }

    public function index()
    {
        $shops  = $this->shopRepository->getAllShops();

        return view('admin.payment_notification.index', compact('shops'));
    }
}
