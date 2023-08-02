<?php

namespace App\Services;

use App\Models\Shop;

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