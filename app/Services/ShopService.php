<?php

namespace App\Services;

use App\Models\Shop;

class ShopService
{
    public function saveShopData($data)
    {
        $shop = new Shop();
        $shop->name = $data['name'];
        $shop->address =  $data['address'];
        $shop->phone_number = $data['phone_number'];
        $shop->save();
    }

    public function updateShopByID($data, $shop)
    {
        $shop->name = $data['name'];
        $shop->address =  $data['address'];
        $shop->phone_number = $data['phone_number'];
        $shop->save();
    }

    public function deleteShopByID($id)
    {
        Shop::destroy($id);
    }
}