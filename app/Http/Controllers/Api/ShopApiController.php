<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShopCreateApiRequest;
use App\Http\Requests\ShopUpdateApiRequest;
use App\Repositories\ShopRepository;
use App\Services\ShopService;
use Illuminate\Support\Facades\Validator;

class ShopApiController extends Controller
{

    protected $shopService;
    protected $shopRepository;

    public function __construct(ShopService $shopService, ShopRepository $shopRepository)
    {
        $this->shopService = $shopService;
        $this->shopRepository = $shopRepository;
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
}
