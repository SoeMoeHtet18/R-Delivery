<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;

class ShopApiController extends Controller
{
    protected $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    public function getAllShopList()
    {
        $shops = $this->shopRepository->getAllShops();
        $shops = $shops->sortByDesc('id');
        return response()->json(['data' => $shops, 'message' => 'Successfully Get Shop List', 'status' => 'success', 200]);
    }
}
