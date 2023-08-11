<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Order;
use App\Models\Shop;
use App\Models\ShopPayment;
use App\Models\TransactionsForShop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShopService
{
    public function saveShopData($data)
    {
        $user = auth()->user();
        $shop = new Shop();
        $shop->name = $data['name'];
        $shop->address =  $data['address'];
        $shop->phone_number = $data['phone_number'];
        $shop->branch_id = $user->branch_id;
        $shop->save();
        return $shop;
    }

    public function updateShopByID($data, $shop)
    {
        $shop->name = $data['name'];
        $shop->address =  $data['address'];
        $shop->phone_number = $data['phone_number'];
        $shop->save();
        return $shop;
    }

    public function deleteShopByID($id)
    {
        Shop::destroy($id);
    }
}